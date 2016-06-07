package com.foozup.common;

public enum Days {
	Sun(1),
	Mon(2),
	Tue(3),
	Wed(4),
	Thu(5),
	Fri(6),
	Sat(7);
	private final int Days;
	Days(int Days) {
        this.Days = Days;
    }
    public int getDaysCode() {
        return this.Days;
    }
    
    public static Days forValue(int value){
    	for( Days d: values()){
    		if(d.getDaysCode()== value){
    			return d;
    		}
    	}
    	throw new IllegalArgumentException("No Enum Found. Invalid day name: " + value );
    }
}
