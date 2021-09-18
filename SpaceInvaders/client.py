import arcade
import json
import os
import socket
import pyglet
import random
import ipaddress
import sys

host = ''  # The server's hostname or IP address
PORT = 4552        # The port used by the server

SCREEN_WIDTH = 800
SCREEN_HEIGHT = 600
SCREEN_TITLE = "Space Invaders"
COLORS = [arcade.color.BYZANTIUM, arcade.color.WHITE]


class gameSetup(arcade.View):
    def __init__(self, window: pyglet.window.Window):
        # basic set up of window
        super().__init__()
        self.window = window
        arcade.set_background_color(arcade.color.BLACK)
        
        self.player_list = None
        self.enemy_list = None
        self.bullet_list = None
        self.enemy_bullet_list = None
        
        self.player = None
        self.numEn = 0
        self.levelNum = 0
        self.bullets = 0
        self.score = 0
        self.enSpeed = 0
        self.frames = 0
        
        self.hitDict = {
            "frame count": 0,
            "bullet center": 0,
            "enemy index": 0
        }
        
        #set up beginning of game
        self.background_list = None
        self.setup()
        
    def setup(self):
        #print("--------------" + host)
        self.servSock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.servSock.connect((host, PORT))
        #set up the basic home page
        self.player_list = arcade.SpriteList()
        self.enemy_list = arcade.SpriteList()
        self.bullet_list = arcade.SpriteList()
        self.enemy_bullet_list = arcade.SpriteList()
        self.background_list = arcade.SpriteList()
        self.background = arcade.load_texture("background.png")
        bg = arcade.Sprite("background.png", center_x = self.window.width/2, center_y = self.window.height/2)
        bg.alpha = 200
        self.background_list.append(bg)
        #on click start game, load level one
        self.player = arcade.Sprite("myship.png", .65)
        self.player.center_x = 300
        self.player.center_y = 50
        self.player_list.append(self.player)
        #this stuff below may be stored in the server and sent over to client? maybe. Who knows
        self.servSock.send(b"first level")
        self.levelDict = json.loads(self.servSock.recv(1024).decode())
        self.levelNum = self.levelDict["level number"]
        self.numEn = self.levelDict["number enemies"]
        self.bullets = self.levelDict["bullet amount"]
        self.enSpeed = self.levelDict["enemy speed"]
        self.score = self.levelDict["score"]
        self.level()
        enWidth = str(self.enemy_list[0].width)
        self.servSock.send(enWidth.encode())
        #end code to start level one
        
    def on_draw(self):
        #render/draw
        arcade.start_render()
        #scale = SCREEN_WIDTH / self.background.width
        #arcade.draw_lrwh_rectangle_textured(0, 0, SCREEN_WIDTH, SCREEN_HEIGHT, self.background)
        self.background_list.draw()
        arcade.draw_text("Level: " + str(self.levelNum) + "     Score: " + str(self.score), 650, 575, arcade.color.BYZANTIUM)
        self.enemy_list.draw()
        self.player_list.draw()
        self.bullet_list.draw()
        self.enemy_bullet_list.draw()
        
    def level(self):
        #setup of a level
        x = 50
        y = 550
        #buildRight = True
        stagger = True
        for enemies in range(self.numEn):
            if x > 750:
                if stagger:
                    x = 75
                    #y -= 100
                    stagger = False
                else:
                    x = 50
                    stagger = True
                y -= 100
            # if x > 750:
                # y -= 50
                # x = 700
                # buildRight = False
            # elif x < 50:
                # y -= 50
                # x = 100
                # buildRight = True
            enemy = arcade.Sprite("enemy.png", .28)
            enemy.center_x = int(x)
            enemy.center_y = int(y)
            enemy.angle = 180
            #if buildRight:
            x += 75
            enemy.change_x = self.enSpeed
            #else:
            #    x -= 50
            #    enemy.change_x = -1 * self.enSpeed
            enemy.change_y = 0
            self.enemy_list.append(enemy)
        self.frames = 0
        
    def on_update(self, delta_time):
        #update lists
        self.frames += 1
        
        self.enemyBullet()
        
        self.enemyBulletUpdate()
        
        self.bulletUpdate()
            
        self.playerUpdate()
        
        self.enemyUpdate()
            
    def playerUpdate(self):
        if self.player.center_x >= SCREEN_WIDTH:
            if self.player.change_x > 0:
                self.player.change_x = 0
        elif self.player.center_x <= 0:
            if self.player.change_x < 0:
                self.player.change_x = 0
        self.player_list.update()
         
    #bullet update information, check for bullet hits and bullets going off screen
    def bulletUpdate(self):
        self.bullet_list.update()
        #check if bullets leave the screen, remove from list of bullets so player can shoot more bullets and there isn't a bullet running infinitely off screen
        for bullet in self.bullet_list:
            if bullet.bottom > SCREEN_HEIGHT:
                bullet.remove_from_sprite_lists()
                
            #initialize a count to get enemy index for server verification
            count = 0
            for enemy in self.enemy_list:
                #if there's a collision, verify with the server
                if arcade.check_for_collision(bullet, enemy): 
                    self.servSock.send(b"verify hit")
                    message = self.servSock.recv(1024)
                    #testing string
                    #print("Index: {0}, Enemy Center: {1}, Bullet Loc: {2}, Frame Count: {3}".format(count, enemy.center_x, bullet.center_x, self.frames))
                    if message.decode() == "READY":
                        #send verifiation information to the server
                        self.hitDict["enemy index"] = count
                        self.hitDict["frame count"] = self.frames
                        self.hitDict["bullet center"] = bullet.center_x
                        self.servSock.send(json.dumps(self.hitDict).encode())
                        message = self.servSock.recv(1024)
                        #if verified, remove enemy/bullet and increment score
                        if message.decode() == "VERIFIED":
                            enemy.remove_from_sprite_lists()
                            bullet.remove_from_sprite_lists()
                            self.score += 10
                        elif message.decode() == "DENIED":
                            print("---SERVER CONNECTION ERROR---")
                            self.gameOver()
                            #sys.exit()
                    else:
                        print("Error in server connection")
                        sys.exit()
                    if len(self.enemy_list) == 0:
                        self.levelComplete()
                count+= 1
     
    #move enemies to next row as needed
    def enemyUpdate(self):
        for enemy in self.enemy_list:
            if enemy.center_x > 750:
                enemy.change_x = -1 * self.enSpeed
                enemy.center_y = enemy.center_y - 50
            if enemy.center_x < 50:
                enemy.center_y = enemy.center_y - 50
                enemy.change_x = self.enSpeed
            if arcade.check_for_collision(enemy, self.player):
                self.gameOver()
        self.enemy_list.update()
      
    #update enemy bullets, remove ones that go off screen and hit player
    def enemyBulletUpdate(self):
        self.enemy_bullet_list.update()
        for bullet in self.enemy_bullet_list:
            if bullet.bottom <= 0:
                bullet.remove_from_sprite_lists()
                #apparently arcade can handle collision detection for you :)
            if arcade.check_for_collision(bullet, self.player):
                bullet.remove_from_sprite_lists()
                self.gameOver()

    #method defining how enemy bullets appear
    def enemyBullet(self):
        for enemy in self.enemy_list:
                enemyShotChance = random.randint(0,100)
                shotSuccessRate = 80
                if self.frames % 120 == 0 and enemyShotChance >= shotSuccessRate or self.frames % 60 == 0 and enemyShotChance >= shotSuccessRate: 
                    bullet = arcade.Sprite("enemy_bullet.png", 0.06)
                    bullet.top = enemy.bottom
                    bullet.center_x = enemy.center_x
                    bullet.change_y = -2
                    bullet.angle = -180
                    self.enemy_bullet_list.append(bullet)
        self.enemy_bullet_list.update()
        
    #method for setting up a new level
    def levelComplete(self):
        #verify with the server that all enemies were destroyed
        self.servSock.send(b"level complete")
        message = self.servSock.recv(1024)
        #get level information
        if message.decode() == "VERIFIED":
            self.servSock.send(b"new level")
            self.levelDict = json.loads(self.servSock.recv(1024).decode())
            self.levelNum = self.levelDict["level number"]
            self.numEn = self.levelDict["number enemies"]
            self.bullets = self.levelDict["bullet amount"]
            self.score = self.levelDict["score"]
            self.enSpeed = self.levelDict["enemy speed"]
            self.level()
        else:
            print("--ERROR in level complete verification--")
            self.gameOver()
        
    #define what happens when a key is pressed
    def on_key_press(self, key, modifiers):
        if key == arcade.key.RIGHT or key == arcade.key.D:
            self.player.change_x = 5
        elif key == arcade.key.LEFT or key == arcade.key.A:
            self.player.change_x = -5
        elif key == arcade.key.UP or key == arcade.key.W:
            if len(self.bullet_list) < self.bullets:
                bullet = arcade.Sprite("bullet.png", 0.06)
                bullet.center_x = self.player.center_x
                bullet.bottom = self.player.top
                bullet.change_y = 4
                self.bullet_list.append(bullet)

    #define what happens when a key is released
    def on_key_release(self, key, modifiers):
        if key == arcade.key.LEFT or key == arcade.key.RIGHT or key == arcade.key.A or key == arcade.key.D:
            self.player.change_x = 0
            
    def gameOver(self):
        #need to send score to server here to be stored or checked against the existing leaderboard could send a true false
        #back if it is a new high score and display on the users end "New Highscore" in the game over screen (optional)
        for enemy in self.enemy_list:
            enemy.remove_from_sprite_lists()
        for bullet in self.bullet_list:
            bullet.remove_from_sprite_lists()
        for bullet in self.enemy_bullet_list:
            bullet.remove_from_sprite_lists()
        
        self.servSock.send(b"game over")
        message = self.servSock.recv(1024).decode()
        try:
            self.score = int(message)
        except ValueError as e:
            print(e)
            sys.exit()
            
        #------COULD NOT FIND BETTER IMPLEMENTATION, COMMENTED OUT FOR LACK OF EFFICIENCY    
        # while True:
            # initials = input("Enter 3 letter initals: ")
            # if len(initials) >= 3:
                # break
        # initials = initials[0:3]
        # self.servSock.send(b"add score")
        # message = self.servSock.recv(1024)
        # if message.decode() == "READY":
            # self.servSock.send(initials.encode())
            
        gameOverView = GameOver(self.window, self.score)
        self.window.show_view(gameOverView)
            
      
#wanted an input box to appear on the screen, but it wouldn't work correctly and couldn't find enough documentation on why it wouldn't draw the button and textbox
class ipView(arcade.View):
    def __init__(self, window: pyglet.window.Window):
        super().__init__()
        self.window = window
        self.IP = 0
        self.center_x = self.window.width/2
        self.center_y = self.window.height/2
        self.text = ""
        arcade.set_background_color(arcade.color.BYZANTIUM)
        self.button_list = None
        self.textbox_list = None
        self.inBox = arcade.TextBox(self.center_x, self.center_y)
        
    def setup(self):
        #self.inBox = arcade.TextBox(self.center_x, self.center_y)
        self.submitButton = self.window.SubmitButton(self.inBox, self.on_submit, self.center_x, self.center_y - 50, width="100", height="50", text="Enter IP for server connection")
        self.button_list.append(self.submitButton)
        #self.textbox_list.append(self.inBox)
    
    def on_draw(self):
        arcade.start_render()
        #self.inBox.draw()
        #self.button_list.draw()
        #self.textbox_list.draw()
        #super().on_draw()
        #for box in self.textbox_list:
        #    box.draw()
        for button in self.button_list:
            button.draw()
        #arcade.draw_text(self.text, 100, 100, arcade.color.BYZANTIUM)
        
    def on_submit(self):
        self.text = self.textbox_list[0].text_storage.text
        try:
            self.IP = ipaddress.ip_address(self.text)
            menu_view = introView(window)
            window.show_view(menu_view)
        except ValueError:
            self.text = "Error, Invalid IP"

#class defining the view for the menu            
class introView(arcade.View):
    def __init__(self, window: pyglet.window.Window):
        super().__init__()
        self.window = window
        self.idx = 0
        
        self.button_list = []
        play_button = playButton(self.window.width/2, self.window.height/2 + 75, self.changeViewClient)
        leader_button = leaderBoardButton(self.window.width/2, self.window.height/2, self.changeViewLB)
        quit_button = exitButton(self.window.width/2, self.window.height/2 - 75, self.exitGame)

        self.button_list.append(play_button)
        self.button_list.append(leader_button)
        self.button_list.append(quit_button)
        
        

    def on_draw(self):
        self.idx += 1
        arcade.start_render()
        arcade.set_background_color(arcade.color.BLACK)
        color = COLORS[(self.idx//20) % 2]
        arcade.draw_text("Space Invaders", self.window.width/2, self.window.height - 50,
                         color, font_size=50, anchor_x="center", anchor_y="top")
        for button in self.button_list:
            button.draw()
        
    def on_mouse_press(self, x, y, button, modifiers):
        check_button_press(x, y, self.button_list)
         
    
    def on_mouse_release(self, x, y, button, modifiers):
        check_button_release(x, y, self.button_list)
   

    def changeViewClient(self):
        client_View = gameSetup(self.window)
        self.window.show_view(client_View)

    def changeViewLB(self):
        leaderboard_view = leaderBoardView(self.window)
        self.window.show_view(leaderboard_view)

    def exitGame(self):
        #close client here as well/disconnect from server
        exit()


   

#somewhere in this view we need to have the leaderboard data sent to it, we can possibly request the data from the server 
#when the person clicks on the button to go to the leaderboard and send the data with the "window" and draw it inside the on_draw

class leaderBoardView(arcade.View):
    def __init__(self, window: pyglet.window.Window):
        super().__init__()
        self.window = window
        arcade.set_background_color(arcade.color.BLACK)
        self.button_list = []
        back_button = backButton(self.window.width/2, self.window.height-550, self.goBack)
        self.button_list.append(back_button)
        
        self.servSock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.servSock.connect((host, PORT))
        self.servSock.send(b"get scores")
        scoreDict = json.loads(self.servSock.recv(1024).decode())
        self.tempScores = []
        self.tempNames = []
        for score in scoreDict:
            self.tempScores.append(score)
            self.tempNames.append(scoreDict[score])
        self.tempScores.sort(reverse= True)

    def on_draw(self):
        arcade.start_render()
        color = "WHITE"
        arcade.draw_text("Top 10", self.window.width/2, self.window.height-50,
                         color, font_size=50, anchor_x="center", anchor_y="top")
        iterate = 0
        deltaY = 200
        for scores in self.tempScores:
            arcade.draw_text(f"{self.tempNames[iterate]} : {scores}\n",self.window.width/2, self.window.height - deltaY, color, font_size= 32, anchor_x= "center")
            iterate += 1 
            deltaY += 35
        for button in self.button_list:
            button.draw()
        

    def on_mouse_press(self, x, y, button, modifiers):
        check_button_press(x, y, self.button_list)
         
    
    def on_mouse_release(self, x, y, button, modifiers):
        check_button_release(x, y, self.button_list)

    def goBack(self):
        intro_view = introView(self.window)
        self.window.show_view(intro_view)

#class defining view for game over screen
class GameOver(arcade.View):
    def __init__(self, window: pyglet.window.Window, score):
        super().__init__()
        self.window = window
        arcade.set_background_color(arcade.color.BLACK)
        self.score = score
        self.button_list = []
        continue_button = continueButton(self.window.width/2, self.window.height-550, self.goBack)
        self.button_list.append(continue_button)

    def on_draw(self):
        arcade.start_render()
        color = "WHITE"
        output = f"Score: {self.score}"
        arcade.draw_text("Game Over!", self.window.width/2, self.window.height - 50,
                         color, font_size=50, anchor_x="center")
        arcade.draw_text(output, self.window.width/2, self.window.height/2, color,
                         font_size=40, anchor_x="center")
        
        for button in self.button_list:
            button.draw()
            

    def on_mouse_press(self, x, y, button, modifiers):
        check_button_press(x, y, self.button_list)

    def on_mouse_release(self, x, y, button, modifiers):
        check_button_release(x, y, self.button_list)

    def goBack(self):
        intro_view = introView(self.window)
        self.window.show_view(intro_view)

#class defining button logic
class buttonLogic:
    def __init__(self,center_x, center_y, width, height, text, font_size = 18, font_face = "Arial",
     face_color = arcade.color.WHITE, highlight_color = arcade.color.BYZANTIUM, shadow_color = arcade.color.BLACK, button_height = 2):
            self.center_x = center_x
            self.center_y = center_y
            self.width = width
            self.height = height
            self.text = text
            self.font_size = font_size
            self.font_face = font_face
            self.pressed = False
            self.face_color = face_color
            self.highlight_color = highlight_color
            self.shadow_color = shadow_color
            self.button_height = button_height
    
    def draw (self):
            arcade.draw_rectangle_filled(self.center_x, self.center_y, self.width, self.height, self.face_color)

            if not self.pressed:
                    color = self.shadow_color
            
            else:
                    color = self.highlight_color

            
            arcade.draw_line (self.center_x - self.width/2, self.center_y - self.height/2, self.center_x + self.width/2,
             self.center_y - self.height/2, color, self.button_height)
            
            arcade.draw_line(self.center_x + self.width / 2, self.center_y - self.height / 2, self.center_x + self.width / 2,
             self.center_y + self.height / 2,color, self.button_height)

            
            if not self.pressed:
                color = self.highlight_color
            else:
                color = self.shadow_color
            
            arcade.draw_line(self.center_x - self.width / 2, self.center_y + self.height / 2,self.center_x + self.width / 2,
             self.center_y + self.height / 2, color, self.button_height)

            arcade.draw_line(self.center_x - self.width / 2, self.center_y - self.height / 2,self.center_x - self.width / 2,
             self.center_y + self.height / 2,color, self.button_height)

            x = self.center_x
            y = self.center_y
            if not self.pressed:
                x -= self.button_height
                y += self.button_height

            arcade.draw_text(self.text, x, y, arcade.color.BLACK, font_size=self.font_size, width=self.width, align="center", anchor_x="center", anchor_y="center")

    def on_press(self):
        self.pressed = True

    def on_release(self):
        self.pressed = False

    
def check_button_press(x, y, buttonList):
    for button in buttonList:
        if x > button.center_x + button.width/2:
            continue
        if x < button.center_x - button.width/2:
            continue
        if y > button.center_y + button.height/2:
            continue
        if y < button.center_y - button.height/2:
            continue
        button.on_press()
    

def check_button_release(x, y, buttonList):
    for button in buttonList:
        if button.pressed:
                button.on_release()

#classes defining where buttons are located to use in views above
class playButton(buttonLogic):
    def __init__ (self, center_x, center_y, action_function,):
        super().__init__(center_x, center_y, 150, 40, "Play", 18, "Arial")
        self.action_function = action_function

    def on_release(self):
        super().on_release()
        self.action_function()


class leaderBoardButton(buttonLogic):
    def __init__ (self, center_x, center_y, action_function,):
        super().__init__(center_x, center_y, 150, 40, "Leader Board", 18, "Arial")
        self.action_function = action_function

    def on_release(self):
        super().on_release()
        self.action_function()

class exitButton (buttonLogic):
    def __init__ (self, center_x, center_y, action_function,):
        super().__init__(center_x, center_y, 150, 40, "Exit", 18, "Arial")
        self.action_function = action_function

    def on_release(self):
        super().on_release()
        self.action_function()

class backButton(buttonLogic):
    def __init__ (self, center_x, center_y, action_function,):
        super().__init__(center_x, center_y, 150, 40, "Back", 18, "Arial")
        self.action_function = action_function

    def on_release(self):
        super().on_release()
        self.action_function()

class continueButton(buttonLogic):
    def __init__ (self, center_x, center_y, action_function,):
        super().__init__(center_x, center_y, 150, 40, "Continue", 18, "Arial")
        self.action_function = action_function

    def on_release(self):
        super().on_release()
        self.action_function()
   
#method to get the IP address to connect to the server, stores in global variable   
def getIP():
    global host
    ipAddr = input("Enter IP Address for server connection: ")
    try:
        ip = ipaddress.ip_address(ipAddr)
        host = ipAddr
    except ValueError:
        print("error")
        sys.exit()

#start the game after getting the IP        
def main():
    getIP()
    window = arcade.Window(width=SCREEN_WIDTH, height = SCREEN_HEIGHT, title = SCREEN_TITLE, resizable = False)
    menu_view = introView(window)
    window.show_view(menu_view)
    arcade.run()

if __name__ == "__main__":
    main()