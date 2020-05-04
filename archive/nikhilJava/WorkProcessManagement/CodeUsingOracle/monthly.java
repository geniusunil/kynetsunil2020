import java.awt.*;
import javax.swing.*;
import java.sql.*;
import java.awt.event.*;

public class monthly extends JFrame implements ActionListener
{
     int idn5,date5,mon5,y5,ndays;
     JButton b1;
     JLabel l1,d,a,sta;
     TextArea ta;


     public monthly()
     {
            b1=new JButton("Close");
            b1.setBounds(200,330,75,20);
            ta=new TextArea(100,100);
            setTitle("Monthly Activities");
       
            setSize(500,410);
            move(120,100);
            l1=new JLabel("Total Month Activities");
            sta=new JLabel("Status");
            l1.setBounds(120,10,300,40);
            l1.setFont(new Font("arial",Font.BOLD,15));
            l1.setForeground(Color.black);
            d=new JLabel("Date");
            d.setBounds(45,50,50,25);
            d.setForeground(Color.black);
            a=new JLabel("Activities");
            a.setBounds(180,50,80,25);
            sta.setBounds(350,50,80,25);
            sta.setForeground(Color.black);
            a.setForeground(Color.black);
            ta.setBounds(40,70,400,200);
            ta.setEditable(false);
            Container c=getContentPane();
            setVisible(true);
            c.setLayout(null);
 
            c.add(b1);c.add(d);c.add(a);
            c.add(l1);c.add(ta);c.add(sta);
          
            b1.addActionListener(this);
     
    }//end of monthly constructor.

   public void send(int a1,int a2,int a3,int a4,int a5)
   {
     idn5=a1;date5=a2;mon5=a3;y5=a4;ndays=a5;
   }//end of send method.

   public void actionPerformed(ActionEvent ae)
   {
        if(ae.getSource()==b1)
        {
           setVisible(false);
        }
   }//end of actionPerformed.
  
  public void process()
  {
         ResultSet rs;
         int sh2=0,eh2=0,sm2=0,em2=0;
         String ds="Message";
 
         for(int i=1;i<=ndays;i++)
         {
             try
             { 
                int k=0;
                          
                Class cfn=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                Connection con=DriverManager.getConnection("Jdbc:Odbc:pro","work","flow");
                Statement st=con.createStatement();
                rs=st.executeQuery("select stime,etime,fback,descr from schedule2  where idno="+idn5+" and dd="+i+" and mm="+mon5+" and yy="+y5+"");
                while(rs.next())                      
                {  
                      k++;
                      ds=rs.getString("descr");
                      if(ds!=null)
                      {
                           if(k==1)
                           {
                               ta.append(i+" /"+mon5+" /"+y5+"\n");
                           }
                           ta.append("                                    "+rs.getTime("stime")+" to "+rs.getTime("etime")+"     :"+ds+"           "+rs.getString("fback")+" \n");
                      }
                      ds=null;
                }
                con.close();
            }
            catch(Exception ec)
            {
                  System.out.println(""+ec);
            }
         }//end of for loop.
     }//end of process method.
           
    /*public static void main(String args[])
    {
      new monthly();
    }*/

}//end of monthly class.
