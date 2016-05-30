package test;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.Comparator;
import java.util.Date;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.StringTokenizer;

import org.supercsv.cellprocessor.FmtDate;
import org.supercsv.cellprocessor.constraint.NotNull;
import org.supercsv.cellprocessor.ift.CellProcessor;
import org.supercsv.io.CsvBeanWriter;
import org.supercsv.io.ICsvBeanWriter;
import org.supercsv.prefs.CsvPreference;

public class CsvTest {

	public static void main(String[] args) {

		try {
		//	String directoryPath = "C:/Users/Home/Desktop/Foozup/UserActivity/";
			String directoryPath="D:/Opswire/UserActivity/"	;
			File myDirectory = new File(directoryPath+"activities/");
			String[] containingFileNames = myDirectory.list();
			List<CsvObject> csvObjectList = new ArrayList<>();

			for (int i = 0; i < containingFileNames.length; i++) {
				String csvFile = directoryPath + "activities/"+containingFileNames[i];
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
			
			Set<String> uniqueActivities=new HashSet<>(); 
			
			Map<String,List<CsvObject>> mapByMonth=new HashMap<>();
			List<CsvObject> janList=new ArrayList<>();
			List<CsvObject> febList=new ArrayList<>();
			List<CsvObject> marList=new ArrayList<>();
			List<CsvObject> aprList=new ArrayList<>();
			for(CsvObject obj:csvObjectList){
				if(obj.getParticipant() !=null && !obj.getParticipant().isEmpty() &&
						!obj.getParticipant().trim().equals("") && !obj.getParticipant().equals("Monitoring_AS") && 
						!obj.getParticipant().equals("OPSWIRE - DMS Internal")
						&& !obj.getParticipant().equals("SwapsWire Test Participant 1")   
						){
					if(	null!=obj.getActivity().trim() && !obj.getActivity().trim().equals("Get Notifications") && !obj.getActivity().trim().equals("Delete Notification")){
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
					
					
					
		        	uniqueActivities.add(obj.getActivity().trim());
				}
				
			}
			System.out.println("Jan : "+janList.size());
			System.out.println("Feb : "+febList.size());
			System.out.println("Mar : "+marList.size());
			System.out.println("Apr : "+aprList.size());
			System.out.println("Unique activities size : "+uniqueActivities.size());
			uniqueActivities.forEach(activity->{
				System.out.println(activity);
			});
			String janFileName = directoryPath+"acitivitesByMonth/Jan_User_Activity.csv";
			String febFileName = directoryPath+"acitivitesByMonth/Feb_User_Activity.csv";
			String marFileName = directoryPath+"acitivitesByMonth/Mar_User_Activity.csv";
			String aprFileName = directoryPath+"acitivitesByMonth/Apr_User_Activity.csv";
		
			writeCSVFileActivities(janFileName,janList);
			writeCSVFileActivities(febFileName,febList);
			writeCSVFileActivities(marFileName,marList);
			writeCSVFileActivities(aprFileName,aprList);
		
			
			
			List<CsvObject> janActivityFilterdList=new ArrayList<>();
			List<CsvObject> febActivityFilterdList=new ArrayList<>();
			List<CsvObject> marActivityFilterdList=new ArrayList<>();
			List<CsvObject> aprActivityFilterdList=new ArrayList<>();
		
			
			List<String> filteredActivityList=getFilteredActivityList();
			List<String> filteredExportActivityList=getExportActivityList();
			HashMap<String,CsvExportObj> exportJanMap= new HashMap<>(); 
			HashMap<String,CsvExportObj> exportFebMap= new HashMap<>(); 
			HashMap<String,CsvExportObj> exportMarMap= new HashMap<>(); 
			HashMap<String,CsvExportObj> exportAprMap= new HashMap<>(); 
			
			HashMap<String,CsvActivitiesCount> activityJanMap= new HashMap<>(); 
			HashMap<String,CsvActivitiesCount> activityFebMap= new HashMap<>(); 
			HashMap<String,CsvActivitiesCount> activityMarMap= new HashMap<>(); 
			HashMap<String,CsvActivitiesCount> activityAprMap= new HashMap<>(); 
			
			
			janList.forEach(action->{
				if(filteredExportActivityList.contains(action.getActivity())){
					janActivityFilterdList.add(action);
					filterExportList(exportJanMap, action);
					filterActivityList(activityJanMap, action);
				}else 
				if(filteredActivityList.contains(action.getActivity())){
					janActivityFilterdList.add(action);
					filterActivityList(activityJanMap, action);
				}
				
			});
			febList.forEach(action->{
				if(filteredExportActivityList.contains(action.getActivity())){
					febActivityFilterdList.add(action);
					filterExportList(exportFebMap, action);
					filterActivityList(activityFebMap, action);
				}else
				if(filteredActivityList.contains(action.getActivity())){
					febActivityFilterdList.add(action);
					filterActivityList(activityFebMap, action);
				}
			});
			marList.forEach(action->{
				if(filteredExportActivityList.contains(action.getActivity())){
					marActivityFilterdList.add(action);
					filterExportList(exportMarMap, action);
					filterActivityList(activityMarMap, action);
				}else
				if(filteredActivityList.contains(action.getActivity())){
					marActivityFilterdList.add(action);
					filterActivityList(activityMarMap, action);
				}
			});
			aprList.forEach(action->{
				if(filteredExportActivityList.contains(action.getActivity())){
					aprActivityFilterdList.add(action);
					filterExportList(exportAprMap, action);
					filterActivityList(activityAprMap, action);
				}else
				if(filteredActivityList.contains(action.getActivity())){
					aprActivityFilterdList.add(action);
					filterActivityList(activityAprMap, action);
				}
			});
			
		
				
				String janfilteredActivityFileName = directoryPath+"acitivitesByMonth/Jan_User_Activity_Filtered.csv";
					String febfilteredActivityFileName = directoryPath+"acitivitesByMonth/Feb_User_Activity_Filtered.csv";
					String marfilteredActivityFileName = directoryPath+"acitivitesByMonth/Mar_User_Activity_Filtered.csv";
					String aprfilteredActivityFileName = directoryPath+"acitivitesByMonth/Apr_User_Activity_Filtered.csv";
					String janfilteredActivityExportFileName = directoryPath+"acitivitesByMonth/Jan_User_Activity_Filtered_Export.csv";
					String febfilteredActivityExportFileName = directoryPath+"acitivitesByMonth/Feb_User_Activity_Filtered_Export.csv";
					String marfilteredActivityExportFileName = directoryPath+"acitivitesByMonth/Mar_User_Activity_Filtered_Export.csv";
					String aprfilteredActivityExportFileName = directoryPath+"acitivitesByMonth/Apr_User_Activity_Filtered_Export.csv";
				
					String janfilteredActivityCountFileName = directoryPath+"acitivitesByMonth/Jan_User_Activity_Filtered_Count.csv";
					String febfilteredActivityCountFileName = directoryPath+"acitivitesByMonth/Feb_User_Activity_Filtered_Count.csv";
					String marfilteredActivityCountFileName = directoryPath+"acitivitesByMonth/Mar_User_Activity_Filtered_Count.csv";
					String aprfilteredActivityCountFileName = directoryPath+"acitivitesByMonth/Apr_User_Activity_Filtered_Count.csv";
				
					
					writeCSVFileActivities(janfilteredActivityFileName,janActivityFilterdList);
					writeCSVFileActivities(febfilteredActivityFileName,febActivityFilterdList);
					writeCSVFileActivities(marfilteredActivityFileName,marActivityFilterdList);
					writeCSVFileActivities(aprfilteredActivityFileName,aprActivityFilterdList);
					
					writeCSVFileExport(janfilteredActivityExportFileName,exportJanMap);
					writeCSVFileExport(febfilteredActivityExportFileName,exportFebMap);
					writeCSVFileExport(marfilteredActivityExportFileName,exportMarMap);
					writeCSVFileExport(aprfilteredActivityExportFileName,exportAprMap);
					
					writeCSVFileActivitiesCount(janfilteredActivityCountFileName,activityJanMap);
					writeCSVFileActivitiesCount(febfilteredActivityCountFileName,activityFebMap);
					writeCSVFileActivitiesCount(marfilteredActivityCountFileName,activityMarMap);
					writeCSVFileActivitiesCount(aprfilteredActivityCountFileName,activityAprMap);
					
					
					
		} catch (Exception e) {
			System.err.println("CSV file cannot be read : " + e);
		}
	}

	private static void filterExportList(HashMap<String, CsvExportObj> exportJanMap, CsvObject action) {
		if(exportJanMap.containsKey(action.getParticipant())){
			System.out.println("count before " + action.getParticipant() +" : "+ 
					exportJanMap.get(action.getParticipant()).getExportCount());
			exportJanMap.get(action.getParticipant()).
			setExportCount(exportJanMap.get(action.getParticipant()).
					getExportCount()+1);
			System.out.println("count after " + action.getParticipant() +" : "+ 
					exportJanMap.get(action.getParticipant()).getExportCount());
		}else{
			CsvExportObj obj=new CsvExportObj();
			obj.setParticipant(action.getParticipant());
			obj.setExportCount(1);
			exportJanMap.put(action.getParticipant(),obj);
		}
	}
	
	private static void filterActivityList(HashMap<String, CsvActivitiesCount> exportJanMap, CsvObject action) {
		if(exportJanMap.containsKey(action.getParticipant())){
			System.out.println("count before " + action.getParticipant() +" : "+ 
					exportJanMap.get(action.getParticipant()).getTotalActivities());
			exportJanMap.get(action.getParticipant()).
			setTotalActivities(exportJanMap.get(action.getParticipant()).
					getTotalActivities()+1);
			System.out.println("count after " + action.getParticipant() +" : "+ 
					exportJanMap.get(action.getParticipant()).getTotalActivities());
		}else{
			CsvActivitiesCount obj=new CsvActivitiesCount();
			obj.setParticipant(action.getParticipant());
			obj.setTotalActivities(1);
			exportJanMap.put(action.getParticipant(),obj);
		}
	}
	

	public static void writeCSVFileActivities(String csvFileName, List<CsvObject> list) {
		ICsvBeanWriter beanWriter = null;
		CellProcessor[] processors = new CellProcessor[] { new FmtDate("MMM/dd/yyyy HH:mm:ss"),
				new NotNull(),new NotNull(), new NotNull(),
				new NotNull()
		};

		/*Comparator<CsvObject> byPartName = (a1, a2) -> a1.getParticipant().compareTo(a2.getParticipant());
	    Comparator<CsvObject> byDate =(a1, a2) -> a1.getDt().compareTo(a2.getDt());
		list.stream().sorted(byPartName.thenComparing(byDate));
		*/
		Comparator<CsvObject> groupByComparator = Comparator.comparing(CsvObject::getParticipant)
                .thenComparing(CsvObject::getDt);
		list.sort(groupByComparator);
		//list.parallelStream().sorted(groupByComparator);
		try {

			beanWriter = new CsvBeanWriter(new FileWriter(csvFileName), CsvPreference.STANDARD_PREFERENCE);
			String[] header = { "dt", "userId", "username", "participant", "activity" };
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

	public static void writeCSVFileExport(String csvFileName, Map<String,CsvExportObj> map) {
		ICsvBeanWriter beanWriter = null;
		CellProcessor[] processors = new CellProcessor[] { 
				new NotNull(), 
				new NotNull(),
		};

		try {
			beanWriter = new CsvBeanWriter(new FileWriter(csvFileName), CsvPreference.STANDARD_PREFERENCE);
			String[] header = { "Participant", "ExportCount" };
			beanWriter.writeHeader(header);
			
					for (Map.Entry<String, CsvExportObj> entry : map.entrySet())
					{
						beanWriter.write(entry.getValue(), header, processors);
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

	public static void writeCSVFileActivitiesCount(String csvFileName, Map<String,CsvActivitiesCount> map) {
		ICsvBeanWriter beanWriter = null;
		CellProcessor[] processors = new CellProcessor[] { 
				new NotNull(), 
				new NotNull(),
		};

		try {
			beanWriter = new CsvBeanWriter(new FileWriter(csvFileName), CsvPreference.STANDARD_PREFERENCE);
			String[] header = { "Participant", "TotalActivities" };
			beanWriter.writeHeader(header);
			
					for (Map.Entry<String, CsvActivitiesCount> entry : map.entrySet())
					{
						beanWriter.write(entry.getValue(), header, processors);
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

	
	public List<CsvObject> filterOutActiviies(List<CsvObject> list) {
		List<CsvObject> filteredList = new ArrayList<>();

		return filteredList;
	}

	public static List<String> getFilteredActivityList() {
		List<String> filteredActivity = new ArrayList<>(Arrays.asList("Clone User", "Unassign Product(s) from User(s)",
				"Clone User Books", "Get Legal Entity Pairs for Trading Permission", "Download Book List Report(xls)",
				"Download Book Report(csv)", "Assign Book(s) to User(s)", "Assign LegalEntity(s) to User(s)",
				"Assign Interest Group(s) to User", "Deactivate Participant Book(s)",
				"Modify Participant Interest Group", "Download Book Report(xls)", //"Get Legal Entity Pairs for MCA",
				"Download Sub Group Report(xls)", "Unassign Book(s) from User(s)", "Download Book List Report(csv)",
				"Update User Profile", "Download Trading Permission Report(csv)",
				"Assign Users to Participant Interest Group", "Reset User Password",
				"Download Participant Overlay Report(xls)", "Download Legal Entity Report(csv)",
				"Download Interest Group Report(csv)", "Download Participant Overlay Report(csv)",
				"Create Participant Book(s)", "Download User Report(xls)",
				"Unassign Users from Participant Interest Group", "Get MasterDocumentType Records",
				"Activate Participant User(s)", "Download Interest Group Report(xls)",
				"Create Participant Interest Group", "Clone Participant Book", "Download Product Report(xls)",
				"Change Password", "Create Participant Product List(s)",// "Add Comments on a Trading Permission LE Pair"
				"Assign Product List(s) to User(s)", "Deactivate Participant Interest Group",
				"Assign User(s) to Subgroup(s)", "Assign Book(s) to Book List", "Unassign Product List(s) from User(s)",
				"Download Legal Entity Report(xls)", "Activate Participant Book(s)", "Download MCA Report(xls)",
				"Create Participant Book List(s)", "Assign Book List(s) to User(s)", "Download User Report(csv)",
				"Unassign Book List(s) from User(s)", "Assign Product(s) to User(s)",
				"Trading Permission Bulk Action Request", "Assign Product(s) to Product List",
				"Unassign Book(s) from Book List", "Clone User Products", "Download Trading Permission Report(xls)",
				"Download Sub Group Report(csv)", "Deactivate Participant User(s)", "User Subscription Change",
				"Unassign LegalEntity(s) from User(s)", "Unassign Interest Group(s) from User"));
		return filteredActivity;
	}

	public static List<String> getExportActivityList() {
		List<String> filteredActivity = new ArrayList<>(Arrays.asList("Download Book List Report(xls)",
				"Download Book Report(csv)","Download Book Report(xls)", 
				"Download Sub Group Report(xls)", "Download Book List Report(csv)",
				"Download Trading Permission Report(csv)","Download Participant Overlay Report(xls)", "Download Legal Entity Report(csv)",
				"Download Interest Group Report(csv)", "Download Participant Overlay Report(csv)",
				 "Download User Report(xls)","Download Interest Group Report(xls)",	"Download Product Report(xls)",
				"Download Legal Entity Report(xls)", "Download MCA Report(xls)","Download User Report(csv)",
				//"Get MasterDocumentType Records","Get Legal Entity Pairs for MCA","Get Legal Entity Pairs for Trading Permission",
				"Download Trading Permission Report(xls)","Download Sub Group Report(csv)"
				));
		//"Get MasterDocumentType Records","Get Legal Entity Pairs for MCA","Get Legal Entity Pairs for Trading Permission", 
		return filteredActivity;
	}

}

/*
 * System.out.println((i+1) +" : "+lineNumber);
 * System.out.println(st.countTokens()); if(st.countTokens() !=5){
 * System.out.println("file no. : "+(i+1)+" file name: "+containingFileNames[i]+
 * " line no: "+lineNumber); }
 */
