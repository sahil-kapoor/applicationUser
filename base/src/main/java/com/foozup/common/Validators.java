package com.foozup.common;

import java.util.regex.Pattern;

public class Validators {

	public boolean validateEmail(String emailId){
		String EMAIL_REGIX = "^[\\\\w!#$%&’*+/=?`{|}~^-]+(?:\\\\.[\\\\w!#$%&’*+/=?`{|}~^-]+)*@(?:[a-zA-Z0-9-]+\\\\.)+[a-zA-Z]{2,6}$";
        Pattern pattern = Pattern.compile(EMAIL_REGIX);
        java.util.regex.Matcher matcher = pattern.matcher(emailId);
        return ((!emailId.isEmpty()) && (emailId!=null) && (matcher.matches()));
		
	}
	
	public boolean isValidNonEmptyString(String str){
		if(null!=str && !str.isEmpty()){
			return true;
		}else{
			return false;
		}
	}
	
	
	public boolean checkLengthString(int len, String str){
		if(isValidNonEmptyString(str)){
			if(str.length()==len){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}
