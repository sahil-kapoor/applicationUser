package test;

import org.mindrot.jbcrypt.BCrypt;

public class Test {

	
	public static void main(String args[]){
		if (BCrypt.checkpw("passfbpass", "$2a$10$TYqJjMMDl6dlMPGMFrxE9efgJnJ1Cj.LaTlviebP8aMzyol122V12"))
			System.out.println("It matches");
		else
			System.out.println("It does not match");
	}
}
