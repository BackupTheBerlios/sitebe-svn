/*
 * Created on 6 juin 2005
 *
 * To change the template for this generated file go to
 * Window>Preferences>Java>Code Generation>Code and Comments
 */

package testXLS;

import java.io.File;
import java.util.Hashtable;

import jxl.CellType;
import jxl.Workbook;
import jxl.format.CellFormat;
import jxl.format.Format;
import jxl.write.Label;
import jxl.write.Number;

/**
 * @author devel
 *
 * To change the template for this generated type comment go to
 * Window>Preferences>Java>Code Generation>Code and Comments
 */
public class JXLInsertImg
    {

    public static void main(String[] args)
        {
        //demande de saisie de note  identifiees
        JXLInsertImg aHSSFInsertImg= new JXLInsertImg();
        // 1 recuperation liste
        aHSSFInsertImg.lireCivilite();
        // 2 presentation web de cette liste est des champs notes a remplire
        
        //3 recuperation de la saisie et construction de la hash (ou autre que hash)
        Hashtable hash = new Hashtable(); 
        //Attention au format des doubles.
        hash.put("titi_piti","12.5");
        hash.put("toto_poto","abs");
        hash.put("tata_pata","18.5");
        //ecriture
        aHSSFInsertImg.ecrireNote(hash);
        }
    
    public void insertImage() 
        {
        
        // TODO Auto-generated constructor stub
        
 try
	        {
     
     //java.io.OutputStream os = new OutputStream(); 
               jxl.Workbook workbook = jxl.Workbook.getWorkbook(new File("c:\\tmp\\XLS\\CRISTAL_5_1_NUIT.xls"));
               jxl.write.WritableWorkbook wwb= Workbook.createWorkbook(new File("c:/tmp/XLS/CRISTAL_5_1_NUIT.xls"),workbook);
              jxl.write.WritableSheet wsheet = wwb.getSheet(0);//wwb.getSheet(0);
              
               File fl =new File("E:/02_Projet/01_Dynastar/00_Dev/EUROPE/FSP-TC08-80.png");
               System.out.println("fl "+fl);
               jxl.write.WritableImage image = new jxl.write.WritableImage(0, 28, 7, 42, fl);
               wsheet.addImage(image);
               System.out.println("wwb "+wwb);
               wwb.write();
               wwb.close();
	        }
        catch(Exception e)
        	{
            e.printStackTrace();
            }
		return;
        }

       public void lireCivilite() 
        {
           
        // TODO Auto-generated constructor stub
        
           try
	        {
			//java.io.OutputStream os = new OutputStream(); 
			jxl.Workbook workbook = jxl.Workbook.getWorkbook(new File("c:\\tmp\\XLS\\source.xls"));
			jxl.write.WritableWorkbook wwb= Workbook.createWorkbook(new File("c:/tmp/XLS/res.xls"),workbook);
			jxl.write.WritableSheet wsheet = wwb.getSheet(0);//wwb.getSheet(0);
			int i,j=0;
			for (i=0;i<4;i++)
                  {
                  for ( j=0;j<4;j++)
                      {
                      if("Nom".equalsIgnoreCase(wsheet.getCell(i,j).getContents().toString()))
                        {
                        break;  
                      	}
                      }
                  if("nom".equalsIgnoreCase(wsheet.getCell(i,j).getContents().toString()))
                      	break;
                  }
              System.out.println(i+" "+j+" ");
              //Extraction nom et prenom
              j=j+1;
              while(!"".equals(wsheet.getCell(i,j).getContents().toString()))
                  {
                  System.out.println(wsheet.getCell(i,j).getContents().toString()+", "+wsheet.getCell(i+1,j++).getContents().toString());
                  }
               wwb.write();
               wwb.close();
	        }
        catch(Exception e)
        	{
            e.printStackTrace();
            }
		return;
        }

       public void ecrireNote(Hashtable hash) 
        {
        
        // TODO Auto-generated constructor stub
        
 try
	        {
     
     //java.io.OutputStream os = new OutputStream(); 
               jxl.Workbook workbook = jxl.Workbook.getWorkbook(new File("c:\\tmp\\XLS\\source.xls"));
               jxl.write.WritableWorkbook wwb= Workbook.createWorkbook(new File("c:/tmp/XLS/res.xls"),workbook);
              jxl.write.WritableSheet wsheet = wwb.getSheet(0);//wwb.getSheet(0);
              int i,j=0;
              for (i=0;i<4;i++)
                  {
                  for ( j=0;j<4;j++)
                      {
                      //System.out.println(wsheet.getCell(i,j).getType());
                      if("Nom".equalsIgnoreCase(wsheet.getCell(i,j).getContents().toString()))
                        {
                        break;  
                      	}
                      }
                  if("nom".equalsIgnoreCase(wsheet.getCell(i,j).getContents().toString()))
                      	break;
                  }
              //System.out.println(i+" "+j+" ");
              //Extraction nom et prenom
              j=j+1;
              while(!"".equals(wsheet.getCell(i,j).getContents().toString()))
                  {
                  String note=(String)hash.get(wsheet.getCell(i,j).getContents().toString()+"_"+wsheet.getCell(i+1,j).getContents().toString());
                  System.out.println("note "+ note);
                  try
                  		{
                      	Number nb0 = new Number(i+2,j,Double.parseDouble(note));
                      	wsheet.addCell(nb0);
                  		}
                  catch(NumberFormatException e)
                  		{
                      System.out.println(e);
                      	Label label0 = new Label(i+2,j, note); 
	                   	wsheet.addCell(label0);
                  		}
                  j++;
                  }
               wwb.write();
               wwb.close();
	        }
        catch(Exception e)
        	{
            e.printStackTrace();
            }
		return;
        }
       
    
    }
