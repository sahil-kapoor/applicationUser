package test;

import java.io.IOException;
import java.io.StringReader;
import java.util.List;

import org.apache.commons.csv.CSVFormat;
import org.apache.commons.csv.CSVParser;
import org.apache.commons.csv.CSVRecord;

public class CSVRead {

	public static void main(String args[]) throws IOException{
		String abc="\"First Name\",\"Last Name\"\n\"First Name\",\"Last Name\"\n\"Sahil,Kapoor\",\"Kapoor\"";
		CSVFormat format=CSVFormat.newFormat(',').withQuote('"');
		CSVParser parser=new CSVParser(new StringReader(abc), format);	
        List csvRecords = parser.getRecords(); 
		for (int i = 2; i < csvRecords.size(); i++) {
               CSVRecord record = (CSVRecord) csvRecords.get(i);
               System.out.println(record.toString() +": " + record.get(1) );
          }

	}
	
}
