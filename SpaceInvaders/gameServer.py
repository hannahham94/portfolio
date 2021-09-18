import socket
from _thread import *
import threading
import json
import sys
import math
import time

HOST = '0.0.0.0'
PORT = 4552

class ClientThread(threading.Thread):
	def __init__(self,clientConn,clientAddr):
		threading.Thread.__init__(self)
		self.clientSocket  = clientConn
		print("thread started for: " + str(clientAddr))
		self.levelDict = {
			"level number": 1,
			"number enemies": 5,
			"bullet amount": 5,
			"enemy speed": 1,
			"score": 0
		}
		self.enemyList = []
		self.bullet_center = 0
		self.enWidth = 0
		self.hitCount = 0
		self.bytesCount = 0
		self.startTime = time.time()
		self.endTime = 0
		
	def run(self):
		while True:
			message = self.clientSocket.recv(1024).decode()
			if message == "close":
				self.clientSocket.send(b"close")
				break
			elif message == "first level":
				self.set_starts()
				self.clientSocket.send(json.dumps(self.levelDict).encode())
				self.bytesCount += sys.getsizeof(json.dumps(self.levelDict).encode())
				try:
					width = self.clientSocket.recv(1024).decode()
					self.bytesCount += sys.getsizeof(width)
					self.enWidth = float(width)
				except ValueError:
					print("SETUP:ERROR - Width received not an integer")
					break
			elif message == "new level":
				#self.levelDict["score"] = self.levelDict["score"] + (10 * self.levelDict["number enemies"])
				self.levelDict["level number"] += 1
				if self.levelDict["level number"] == 10:
					self.levelDict["enemy speed"] = 1.2
				if self.levelDict["level number"] % 3 == True:
					self.levelDict["enemy speed"] = float(self.levelDict["enemy speed"] + .2)
				if self.levelDict["level number"] % 5 == True and self.levelDict["bullet amount"] > 2:
					self.levelDict["bullet amount"] -= 1
				if self.levelDict["number enemies"] < 18:
					if self.levelDict["level number"] >= 10:
						self.levelDict["number enemies"] += 1
					else:
						self.levelDict["number enemies"] += 2
				self.set_starts()
				self.clientSocket.send(json.dumps(self.levelDict).encode())
				self.bytesCount += sys.getsizeof(json.dumps(self.levelDict).encode())
			#LEADER BOARD LOGICS - only get scores was finished
			# elif message == "add score":
				# #get score from server 
				# score = self.levelDict["score"]
				# #implement code to get initials
				# self.clientSocket.send(b"READY")
				# initials = self.clientSocket.recv(1024).decode()
				# outFile = open('high_scores.txt', 'a')
				# addLine = "\n" + str(score) + "," + initials
				# outFile.writelines(addLine)
				# outFile.close()
			elif message == "get scores":
				scoreDict = {}
				tempDict = {}
				inFile = open('high_scores.txt', 'r')
				#scores = inFile.read()
				for line in inFile:
					line = line.strip()
					#print(line)
					temp = line.split(',')
					tempDict[int(temp[0])] = temp[1]
				count = 1
				for i in sorted(tempDict.keys(), reverse = True):
					#print(i)
					if count == 11:
						break
					scoreDict[i] = tempDict[i]
					count += 1
				self.clientSocket.send(json.dumps(scoreDict).encode())
			elif message == "verify hit":
				self.clientSocket.send(b'READY')
				self.bytesCount += sys.getsizeof(b'READY')
				stats = json.loads(self.clientSocket.recv(1024).decode())
				self.bytesCount += sys.getsizeof(stats)
				if self.check_hit(stats["bullet center"], stats["frame count"], stats["enemy index"]):
					self.hitCount += 1
					self.levelDict["score"] += 10
					del self.enemyList[stats["enemy index"]]
					self.clientSocket.send(b'VERIFIED')
					self.bytesCount += sys.getsizeof(b'VERIFIED')
				else:
					self.clientSocket.send(b'DENIED')
					self.bytesCount += sys.getsizeof(b'DENIED')
					print("VERIFICATION:ERROR - Bullet collision not verified, closing connection")
					#break
			elif message == "level complete":
				if self.hitCount == self.levelDict["number enemies"]:
					self.clientSocket.send(b'VERIFIED')
					self.bytesCount += sys.getsizeof(b'VERIFIED')
					self.hitCount = 0
				else:
					self.clientSocket.send(b'DENIED')
					self.bytesCount += sys.getsizeof(b'DENIED')
					print("VERIFICATION:ERROR - Level complete verification failed, closing connection")
					break
			elif message == "game over":
				scoreString = str(self.levelDict["score"])
				self.bytesCount += sys.getsizeof(scoreString.encode())
				self.clientSocket.send(scoreString.encode())
		self.endTime = time.time()
		totalTime = self.endTime - self.startTime
		print("Time: {0}, Bytes: {1}".format(totalTime, self.bytesCount))
		self.clientSocket.close()
		
	#store the location each enemy will start at
	def set_starts(self):
		self.enemyList = []
		place = 50
		stagger = True
		for enemy in range(0, self.levelDict["number enemies"]):
			if place > 750:
				if stagger:
					place = 75
					stagger = False
				else:
					place = 50
					stagger = True
			self.enemyList.append(place)
			place += 75
				
	#method to check location of enemy for verification of bullet collision
	def check_hit(self, targetLoc, frameCount, enIndex):
		start = self.enemyList[enIndex]
		movement = frameCount * self.levelDict["enemy speed"]
		end = 0
		direction = ""
		loc = movement + start
		if loc > 750:
			#gets pixels past 750 and get which row the enemy is on
			end = (loc - 50) / 700
			loc = loc % 750	 
			if math.floor(end % 2) != 0:
				end = 750 - loc
				direction = "left"
			else:
				end = 50 + loc
				direction = "right"
		else:
			end = loc
		
		#bullet(targetLoc) is greater than or equal to left side of enemy and less than or equal to right side of enemy
		if targetLoc >= end - self.enWidth and targetLoc <= end + self.enWidth:
			return True
		elif (math.floor(movement/700)) != 0:
			calcOffset = 2 * (math.floor(movement/700) + (self.levelDict["enemy speed"] * math.floor(movement/700)))
			#print(calcOffset)
			if direction == "left":
				if targetLoc <= (targetLoc - calcOffset) + self.enWidth and targetLoc >= (targetLoc - calcOffset) - self.enWidth:
					#print("End: {0}, Bullet Loc: {1}, Left Bound: {2}, Right Bound: {3}".format(end, targetLoc, end - self.enWidth, end + self.enWidth))
					return True
				else:
					return False
			elif direction == "right":
				if targetLoc <= (targetLoc + calcOffset) + self.enWidth and targetLoc >= (targetLoc + calcOffset) - self.enWidth:
					#print("End: {0}, Bullet Loc: {1}, Left Bound: {2}, Right Bound: {3}".format(end, targetLoc, end - self.enWidth, end + self.enWidth))
					return True
				else:
					return False
			else:
				return False 
		else:
			print("Movement: {0}".format(movement))
			print("Start: {0}, Frame Count: {1}".format(start, frameCount))
			print("End: {0}, Bullet Loc: {1}, Left Bound: {2}, Right Bound: {3}".format(end, targetLoc, end - self.enWidth, end + self.enWidth))
			return False
		

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.bind((HOST, PORT))
s.listen(30)
#threads = []

while True:
	try:
		clientConn, clientAddr = s.accept()
		newThread = ClientThread(clientConn, clientAddr)
		newThread.daemon = True
		newThread.start()
		#threads.append(newThread)
	except KeyboardInterrupt:
		print("-_-_-_- Closing Server -_-_-_-")
		clientConn.close()
		break