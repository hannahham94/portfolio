using System;
using System.Collections.Generic;
				
public class TreeNode {
	public TreeNode left = null;
	public TreeNode right = null;
	public char id;
	
	public TreeNode(char nodeId) {
		id = nodeId;
	}
	
	public void setNodes(TreeNode leftNode, TreeNode rightNode) {
		left = leftNode;
		right = rightNode;
	}
}

public class Program
{
	public static void Main()
	{
		//setting up a random tree here
		TreeNode nodeA = new TreeNode('a');
		TreeNode nodeB = new TreeNode('b');
		TreeNode nodeC = new TreeNode('c');
		TreeNode nodeD = new TreeNode('d');
		TreeNode nodeE = new TreeNode('e');
		TreeNode nodeF = new TreeNode('f');
		TreeNode nodeG = new TreeNode('g');
		
		nodeA.setNodes(nodeB, nodeC);
		nodeB.setNodes(nodeD, nodeE);
		nodeC.setNodes(nodeF, null);
		nodeD.setNodes(nodeG, null);
		
		HashSet<int> heightsVisited = new HashSet<int>();
		Console.WriteLine(nodeA.id);
		heightsVisited.Add(0);
		searchNodes(nodeA, heightsVisited, 0);
	}
	
	public static void searchNodes(TreeNode cur, HashSet<int> heightsVisited,  int height) {
		if(cur == null) {
			return;
		}
		else if(!heightsVisited.Contains(height)) {
			Console.WriteLine(cur.id);
			heightsVisited.Add(height);
		}
		
		searchNodes(cur.right, heightsVisited, height + 1);
		searchNodes(cur.left, heightsVisited, height + 1);
	}
}
