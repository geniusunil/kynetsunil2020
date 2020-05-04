import java.awt.event.*;
import java.awt.*;
import javax.swing.*;
import javax.swing.event.*;
import java.sql.*;
import java.util.Calendar;

  public class todays extends JFrame implements ActionListener
  {
         Runtime r;int id=0;
         JLabel td, name,lname,l1,l2,l3,l4,status;
         JButton b1;
         TextArea ta;
 
         Calendar cal=Calendar.getInstance();
    
         String selectedname=null;

         public todays()
         {
                setTitle("Todays Activities");
                setSize(500,500);
                move(120,60);
                td=new JLabel();
                b1=new JButton("Calender");
                l1=new JLabel(" Activities For Today Are");
                l2=new JLabel("StartTime");
                l3=new JLabel("EndTime");
                l4=new JLabel("Activity");
                status=new JLabel("Status");
                name=new JLabel("Welcome");
                lname=new JLabel();
                ta=new TextArea();
                name.setBounds(60,35,80,20);
                lname.setBounds(145,35,80,20);
                name.setForeground(Color.black);
                lname.setForeground(Color.black);
                l1.setBounds(57,70,400,20);
                l1.setForeground(Color.black);
                td.setBounds(300,10,100,20);
                td.setForeground(Color.black);
                l2.setBounds(62,100,80,20);
                l3.setBounds(160,100,80,20);
                l4.setBounds(275,100,80,20);
                status.setBounds(369,100,80,20);
                l1.setForeground(Color.black);
                l2.setForeground(Color.black);
                l3.setForeground(Color.black);
                l4.setForeground(Color.black);
                status.setForeground(Color.black);
                ta.setBounds(60,120,400,150);
                ta.setEditable(false);
        
                b1.setBounds(160,320,120,30);
 
                Container cc=getContentPane();
                cc.setLayout(null);
                cc.add(l1);cc.add(l2);cc.add(l3);
                cc.add(l4);cc.add(status);
                cc.add(ta);cc.add(b1);
                cc.add(lname);cc.add(name);cc.add(td);
   
                setVisible(true);
       
                b1.addActionListener(this);
         }//end of todays constuctor.
      
        public void senditems(String sn)
        {
               selectedname=sn;
        }//end of senditems method.
   
        public void display()
        {
               ResultSet rs;
               int sh2=0,eh2=0,sm2=0,em2=0;
               int d,m,y;
               d=cal.get(Calendar.DATE);
               m=cal.get(Calendar.MONTH)+1;
               y=cal.get(Calendar.YEAR);
               String ds=null;
               lname.setText(selectedname);
               td.setText(""+d+"/ "+m+"/ "+y+"");
                  try
                  {
                    Class cfn=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                    Connection con=DriverManager.getConnection("Jdbc:Odbc:pro");
                    Statement st=con.createStatement();
                    System.out.println("select stime,etime,fback,desc from schedule2  where idno="+id+" and dd="+d+" and mm="+m+" and yy="+y);
                    rs=st.executeQuery("select stime,etime,fback,desc from schedule2  where idno="+id+" and dd="+d+" and mm="+m+" and yy="+y);

                    while(rs.next())
                    {
                          ds=rs.getString("desc");
                          ta.append("  "+rs.getTime("stime")+"                "+rs.getTime("etime")+"                      "+ds+"                  "+rs.getString("fback")+" \n");
                          ta.append("--------------------------------------------------------------------------------------------------------------------------------------------\n");
                    }
                    con.close();
                  }
                  catch(Exception ec)
                  {
                        System.out.println(""+ec);
                  }
         }//end of display method.

         public void method()
         {
             try
             {
               Class cc=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
               Connection con=DriverManager.getConnection("jdbc:odbc:pro");
               Statement st=con.createStatement();
               ResultSet rs=st.executeQuery("select idno from regs where l_name='"+selectedname+"'");
  
               while(rs.next())
               {
                    id=rs.getInt("idno");
                    System.out.println(""+id);
               }
               con.close();
            }
            catch(Exception ee5)
            {
                  System.out.println(" "+ee5);
            }
       }//end of void method.

     public void actionPerformed(ActionEvent ae)
     {
            Object o=ae.getSource();
            if(o==b1)
            {
               try
               {  
                  if(Option.i==3)
                  {
                     rtabcal rtt=new rtabcal();
                     r=Runtime.getRuntime();
                     String file="rtabcal";
                     r.exec("java"+" "+file);
                     rtt.sname(selectedname,id);
                     this.setVisible(false);
                 }
                 else
                 { 
                     tabcal tbb=new tabcal();
                     r=Runtime.getRuntime();
                     String file="tabcal";
                     r.exec("java"+" "+file);
                     System.out.println(""+id);
                     tbb.sname(selectedname,id);
                     this.setVisible(false);
                 }
              }
              catch(Exception eee)
              {
                   System.out.println(""+eee);
              }
            }
         }//end of actionPerformed.
 
      /* public static void main(String a[])
         {
               new todays();
         }*/

}//end of todays class.
