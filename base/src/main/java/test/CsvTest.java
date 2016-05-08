package test;

import java.awt.print.Book;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.StringTokenizer;

import org.supercsv.cellprocessor.FmtDate;
import org.supercsv.cellprocessor.ParseDouble;
import org.supercsv.cellprocessor.constraint.NotNull;
import org.supercsv.cellprocessor.ift.CellProcessor;
import org.supercsv.io.CsvBeanWriter;
import org.supercsv.io.ICsvBeanWriter;
import org.supercsv.prefs.CsvPreference;

public class CsvTest {

	public static void main(String[] args) {

		try {
			String directoryPath = "C:/Users/Home/Desktop/Foozup/UserActivity/";

			File myDirectory = new File(directoryPath);
			String[] containingFileNames = myDirectory.list();
			List<CsvObject> csvObjectList = new ArrayList<>();

			for (int i = 0; i < containingFileNames.length; i++) {
				String csvFile = directoryPath + containingFileNames[i];
				BufferedReader br = new BufferedReader(new FileReader(csvFile));
				String line = "";
				StringTokenizer st = null;
				int lineNumber = 0;
				int tokenNumber = 0;
				while ((line = br.readLine()) != null) {
					if (lineNumber == 0) {
						System.out.println(line + "containingFileNames " + containingFileNames[i] + "  line no : "+ lineNumber);
					} else {
						st = new StringTokenizer(line, ",");
						//tokenNumber++;
						CsvObject csvObj = new CsvObject();
						while (st.hasMoreTokens()) {
							//tokenNumber++;
							System.out.println("line number : "+lineNumber);
							String dtString = st.nextToken();
							SimpleDateFormat format = new SimpleDateFormat("dd MMM yyyy;HH:mm:ss.SSS");
							Date date = format.parse(dtString);
							csvObj.setDt(date);
							csvObj.setUserId(st.nextToken());
							csvObj.setUsername(st.nextToken());
							csvObj.setParticipant(st.nextToken().replaceAll("\"", "").trim());
							csvObj.setActivity(st.nextToken().trim());
							csvObjectList.add(csvObj);
							// System.out.println(st.countTokens());
							// display csv values
							// System.out.print(st.nextToken() + "
							// "+tokenNumber);
							
							
						}
					}
					lineNumber++;
					tokenNumber = 0;
				}
				System.out.println(csvObjectList.size());
			}

			//ignore monitoring, opswire and blank participant
			
			 
			
			Map<String,List<CsvObject>> mapByMonth=new HashMap<>();
			List<CsvObject> janList=new ArrayList<>();
			List<CsvObject> febList=new ArrayList<>();
			List<CsvObject> marList=new ArrayList<>();
			List<CsvObject> aprList=new ArrayList<>();
			for(CsvObject obj:csvObjectList){
				if(obj.getParticipant() !=null && !obj.getParticipant().isEmpty() &&
						!obj.getParticipant().trim().equals("") && !obj.getParticipant().equals("Monitoring_AS") && 
						!obj.getParticipant().equals("OPSWIRE - DMS Internal")
						&& !obj.getParticipant().equals("SwapsWire Test Participant 1")){
					Calendar cal1 = Calendar.getInstance();
		        	cal1.setTime(obj.getDt());
		        	if(cal1.get(Calendar.MONTH)==0){
		        		janList.add(obj);
		        	}else if(cal1.get(Calendar.MONTH)==1){
		        		febList.add(obj);
					}else if(cal1.get(Calendar.MONTH)==2){
		        		marList.add(obj);
					}else if(cal1.get(Calendar.MONTH)==3){
		        		aprList.add(obj);
					}
				}
				
			}
			System.out.println("Jan : "+janList.size());
			System.out.println("Feb : "+febList.size());
			System.out.println("Mar : "+marList.size());
			System.out.println("Apr : "+aprList.size());
			
			String janFileName = "C:/Users/Home/Desktop/Foozup/jan.csv";
			String febFileName = "C:/Users/Home/Desktop/Foozup/feb.csv";
			String marFileName = "C:/Users/Home/Desktop/Foozup/mar.csv";
			String aprFileName = "C:/Users/Home/Desktop/Foozup/apr.csv";
			writeCSVFile(janFileName,janList);
			writeCSVFile(febFileName,febList);
			writeCSVFile(marFileName,marList);
			writeCSVFile(aprFileName,aprList);
			
		} catch (Exception e) {
			System.err.println("CSV file cannot be read : " + e);
		}
	}
	
	public static void writeCSVFile(String csvFileName, List<CsvObject> list) {
	    ICsvBeanWriter beanWriter = null;
	    CellProcessor[] processors = new CellProcessor[] {
	    		new FmtDate("MMM/dd/yyyy"), // ISBN
	            new NotNull(), // title
	            new NotNull(), // author
	            new NotNull(), // publisher
	            new NotNull(), // title
	            
	    };
	 
	    try {
	    	
	        beanWriter = new CsvBeanWriter(new FileWriter(csvFileName),
	                CsvPreference.STANDARD_PREFERENCE);
	        String[] header = {"dt", "userId", "username", "participant", "activity"};
	        beanWriter.writeHeader(header);
	 
	        for (CsvObject csvObj : list) {
	            beanWriter.write(csvObj, header, processors);
	        }
	 
	    } catch (IOException ex) {
	        System.err.println("Error writing the CSV file: " + ex);
	    } finally {
	        if (beanWriter != null) {
	            try {
	                beanWriter.close();
	            } catch (IOException ex) {
	                System.err.println("Error closing the writer: " + ex);
	            }
	        }
	    }
	}

}

/*
 * System.out.println((i+1) +" : "+lineNumber);
 * System.out.println(st.countTokens()); if(st.countTokens() !=5){
 * System.out.println("file no. : "+(i+1)+" file name: "+containingFileNames[i]+
 * " line no: "+lineNumber); }
 */






