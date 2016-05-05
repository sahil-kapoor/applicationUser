package test;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.StringTokenizer;

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
							csvObj.setParticipant(st.nextToken());
							csvObj.setActivity(st.nextToken());
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

		} catch (Exception e) {
			System.err.println("CSV file cannot be read : " + e);
		}
	}

}

/*
 * System.out.println((i+1) +" : "+lineNumber);
 * System.out.println(st.countTokens()); if(st.countTokens() !=5){
 * System.out.println("file no. : "+(i+1)+" file name: "+containingFileNames[i]+
 * " line no: "+lineNumber); }
 */
