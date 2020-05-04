import javax.swing.*;
import javax.swing.event.*;
import java.awt.*;
import java.awt.event.*;
import java.sql.*;
import javax.swing.border.*;
import java.util.*;


public class details extends JFrame implements ActionListener
{
    JLabel nl,np,stl,stp,etl,etp,desl,desp,detl,prel,prep;
    JTextArea ta;
    JButton close,feed,submit,postp,dchange,atten;
    String msg,oname,pref;
    int idn,d,m,y,descnum;
    String s3=null;
    JLabel p1,p2,pp1,pp2,p3,p4,dl,ml,yl;
    JComboBox pd,pm,py,psh1,psm1,psh2,psm2;
    JPanel panell;
    String border_type="Line";
    AbstractBorder border=new LineBorder(Color.black,1);

    String[] hours={ "01","02","03","04","05","06",
                  "07","08","09","10","11","12",
                  "13","14","15","16","17","18",
                  "19","20","21","22","23"};

    String[] minits={"0", "05","10","15","20",
                   "25","30","35","40",
                   "45","50","55"};

      String year[]={"2000","2001","2002","2003","2004","2005","2006","2007","2008","2009","2010","2011","2012","2013","2014","2015"};
      String month[]={"01","02","03","04","05","06","07","08","09","10","11","12"};
      String date[]={"01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"};
      int ch=0;
      Time sti,eti;

   public details()
   {
           setTitle("Details");
           setSize(415,420);
           Container c=getContentPane();
           c.setLayout(null);
           move(200,85);
          nl    =new JLabel("NAME:");
          nl.setForeground(Color.black);
          np    =new JLabel();
          np.setForeground(Color.black);
          stl   =new JLabel("StartTime:");
          stl.setForeground(Color.black);
          stp   =new JLabel();
          stp.setForeground(Color.black);
          etl   =new JLabel("EndTime:");
          etl.setForeground(Color.black);
          etp   =new JLabel();
          etp.setForeground(Color.black);
          desl  =new JLabel("DESCRIPTION:");
          desl.setForeground(Color.black);
          desp  =new JLabel();
          desp.setForeground(Color.black);
          detl  =new JLabel("DETAILS:");
          detl.setForeground(Color.black);
          ta    =new JTextArea(30,30);
          ta.setEditable(false);
          close  =new JButton("Close");
          feed   =new JButton("FeedBack");
          submit =new JButton("Submit");
          postp  =new JButton("PostPone");
          dchange=new JButton("ChangeDetails");
          atten=new JButton("Attended");
          prel   =new JLabel("PREFERENCE:");
          prel.setForeground(Color.black);
          prep   =new JLabel();
          prep.setForeground(Color.black);
          p1=new JLabel("Hr");
          p1.setForeground(Color.black);
          p2=new JLabel("Mn");
          p2.setForeground(Color.black);
          pp1=new JLabel("Hr");
          pp1.setForeground(Color.black);
          pp2=new JLabel("Mn");
          pp2.setForeground(Color.black);
          dl=new JLabel("DD");
          ml=new JLabel("MM");
          yl=new JLabel("YY");
          dl.setForeground(Color.black);
          ml.setForeground(Color.black);
          yl.setForeground(Color.black);
          p3=new JLabel("Changed Date:");
          p3.setForeground(Color.black);
          pd=new JComboBox(date);
          pm=new JComboBox(month);
          py=new JComboBox(year);
          psh1=new JComboBox(hours);
          psm1=new JComboBox(minits);
          psh2=new JComboBox(hours);
          psm2=new JComboBox(minits);
          p3.setBounds(30,50,100,20);

          dl.setBounds(125,30,30,20);
          pd.setBounds(120,50,50,20);

          ml.setBounds(185,30,30,20);
          pm.setBounds(172,50,50,20);

          yl.setBounds(245,30,30,20);
          py.setBounds(222,50,60,20);

          p1.setBounds(125,70,30,20);
          psh1.setBounds(120,90,50,20);

          p2.setBounds(180,70,30,20);
          psm1.setBounds(170,90,50,20);

          pp1.setBounds(305,70,30,20);
          psh2.setBounds(300,90,50,20);

          pp2.setBounds(365,70,30,20);
          psm2.setBounds(350,90,50,20);

     nl.setBounds(30,10,80,20);
     np.setBounds(120,10,100,20);
     stl.setBounds(30,90,100,20);
     stp.setBounds(90,90,100,20);
     etl.setBounds(240,90,100,20);
     etp.setBounds(295,90,100,20);
     desl.setBounds(30,110,130,20);
     desp.setBounds(180,110,130,20);
     detl.setBounds(30,170,120,20);
     prel.setBounds(30,140,120,20);
     prep.setBounds(180,140,120,20);
     ta.setBounds(110,170,260,60);
     feed.setBounds(120,250,100,20);
     submit.setBounds(245,322,80,20);
     postp.setBounds(230,250,120,20);
     dchange.setBounds(230,273,120,20);
     atten.setBounds(230,297,120,20);
     close.setBounds(200,345,70,20);
     c.add(nl);c.add(np);c.add(stl);
     c.add(stp);c.add(etl);c.add(etp);
     c.add(desl);c.add(desp);c.add(prel);
     c.add(prep);c.add(detl);
     c.add(pp1);c.add(pp2);c.add(ta);c.add(dl);
     c.add(ml);c.add(yl);c.add(close);
     c.add(feed);c.add(submit);c.add(postp);
     c.add(p1);c.add(p2);c.add(p3);
     c.add(pd);c.add(pm);c.add(py);
     c.add(psh1);c.add(psm1);c.add(psh2);
     c.add(psm2);c.add(dchange);
     c.add(atten);//c.add(ta);
     postp.setVisible(false);
     atten.setVisible(false);
     dchange.setVisible(false);
     p3.setVisible(false);
     pd.setVisible(false);
     pm.setVisible(false);
     py.setVisible(false);
     psh1.setVisible(false);
     psm1.setVisible(false);
     psh2.setVisible(false);
     psm2.setVisible(false);
     submit.setVisible(false);
     p1.setVisible(false);
     p2.setVisible(false);
     pp1.setVisible(false);
     pp2.setVisible(false);
     dl.setVisible(false);
     ml.setVisible(false);
     yl.setVisible(false);
     
     close.addActionListener(this);
     feed.addActionListener(this);
     submit.addActionListener(this);
     dchange.addActionListener(this);
     postp.addActionListener(this);
     atten.addActionListener(this);
     panell=new JPanel();
     panell.setBounds(110,240,260,117);
     panell.setBackground(Color.gray);
     if(border_type.equals("Line"))
     {
      c.add(panell);
      panell.setBorder(border);
      repaint();
     }
     setVisible(true);
    }

     public void senddetails(String msg1,String oname1,int id,int date,int month,int year)
     {
      msg=msg1;
      oname=oname1;
      idn=id;
      d=date;m=month;
      y=year;
      System.out.println("msg="+msg);
     }

   public void process()
   {
         String detail=null;
         String s1=msg.substring(0,2);
         int   t1=Integer.parseInt(s1);
         String s11=msg.substring(3,5);
         int   t11=Integer.parseInt(s11);
         Time st1=new Time(t1,t11,00);
         String s2=msg.substring(10,12);
         int   t2=Integer.parseInt(s2);
         String s21=msg.substring(13,15);
         int   t21=Integer.parseInt(s21);
         Time et1=new Time(t2,t21,00);

         s3=msg.substring(19,msg.length());

                    ResultSet rs;
                    
                     try
                       {
                           Class cfn=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                           Connection con=DriverManager.getConnection("Jdbc:Odbc:pro");
                           Statement st=con.createStatement();
                           rs=st.executeQuery("select details,preference,descno from schedule2  where idno="+idn+" and dd="+d+" and mm="+m+" and yy="+y+" and desc='"+s3+"'");
                           
                           while(rs.next())
                           {
                            descnum=rs.getInt("descno");
                            pref=rs.getString("preference");
                            detail=rs.getString("details");
                           }
                           con.close();
                        }
                      
                          catch(Exception ec)
                          {
                           System.out.println(""+ec);
                          }

         np.setText(oname);
         stp.setText(""+st1);
         etp.setText(""+et1);
         prep.setText(""+pref);
         desp.setText(""+s3);
         ta.setText(detail);

    }
                                                            
    
    public void actionPerformed(ActionEvent ae)
    {   
      if(ae.getSource()==close)
      {
       this.setVisible(false);
      }
      else
         if(ae.getSource()==feed)
         {
           postp.setVisible(true);
           dchange.setVisible(true);
           submit.setVisible(true);
           atten.setVisible(true);
         }
         else
         if(ae.getSource()==postp)
         {
            p3.setVisible(true);
            pd.setVisible(true);
            pm.setVisible(true);
            py.setVisible(true);
            psh1.setVisible(true);
            psm1.setVisible(true);
            psh2.setVisible(true);
            psm2.setVisible(true);
            p1.setVisible(true);
            p2.setVisible(true);
            pp1.setVisible(true);
            pp2.setVisible(true);
            dl.setVisible(true);
            ml.setVisible(true);
            yl.setVisible(true);

            stp.setVisible(false);
            etp.setVisible(false);
            ch=1;
         }
         else
         if(ae.getSource()==atten)
         {
            ch=3;
         }
         else
         if(ae.getSource()==dchange)
         {
          ta.setEditable(true);
          ch=2;
         }
         else
         if(ae.getSource()==submit)
         {
               int d1 =Integer.parseInt((String)pd.getSelectedItem());
               int m1 =Integer.parseInt((String)pm.getSelectedItem());
               int y1 =Integer.parseInt((String)py.getSelectedItem());
               int sh=Integer.parseInt((String)psh1.getSelectedItem());
               int sm=Integer.parseInt((String)psm1.getSelectedItem());
               Time sti=new Time(sh,sm,00);
               String st=""+sh+":"+sm+"";
               int eh=Integer.parseInt((String)psh2.getSelectedItem());
               int em=Integer.parseInt((String)psm2.getSelectedItem());
               Time eti=new Time(eh,em,00);
               String et=""+eh+":"+em+"";
               String post="PostPone";
               String po="Yet To Attend";
               String fb="Attended";
               Vector vm=new Vector(10,10);
               int uid[];
                   int r;
               try
                  {
                    Class cfn=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                    Connection con=DriverManager.getConnection("Jdbc:Odbc:pro");
                    Statement sta=con.createStatement();
                    ResultSet rs=sta.executeQuery("select idno from schedule2 where  dd="+d+" and mm="+m+" and yy="+y+" and desc='"+s3+"' ");
                    while(rs.next())
                    {
                     int id=rs.getInt("idno");
                     vm.addElement(""+id);

                    }

                    Enumeration  enum=vm.elements();
                    uid=new int[vm.size()];
                    int p=0;
                    while(enum.hasMoreElements())
                    {
                     int ll=Integer.parseInt((String)enum.nextElement());
                     uid[p]=ll;
                     p++;

                    }
                  for(int i=0;i<uid.length;i++)
                  {


                    if(ch==2)
                       {
                          r=sta.executeUpdate("update schedule2 set details='"+ta.getText()+"' where idno="+uid[i]+" and dd="+d+" and mm="+m+" and yy="+y+" and desc='"+s3+"' ");
                       }
                      else                                                                            //idn
                          if(ch==3)
                          {
                            r=sta.executeUpdate("update schedule2 set fback='"+fb+"' where idno="+uid[i]+" and dd="+d+" and mm="+m+" and yy="+y+" and desc='"+s3+"'");
                          }

                    else
                     if(ch==1)
                       {
                          r=sta.executeUpdate("update schedule2 set fback='"+post+"' where idno="+uid[i]+" and dd="+d+" and mm="+m+" and yy="+y+" and desc='"+s3+"'");
                          int count=sta.executeUpdate("insert into schedule2 values("+uid[i]+","+d1+","+m1+","+y1+",'"+sti+"','"+eti+"','"+pref+"',"+descnum+",'"+s3+"','"+po+"','"+ta.getText()+"')");
                       
                       }

                  }
                       Mcheck mck=new Mcheck(this,"Ok Submitted");
                  


                       con.close();
                  }
                      
                    catch(Exception ec)
                     {
                       System.out.println(""+ec);
                     }
           
         }

    }

 /*   public void method()
    {

                     boolean check1=false;
                     Time stim,etim;
                  try
                     {
                       Class cfn1=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                       Connection con1=DriverManager.getConnection("Jdbc:Odbc:pro");
                       Statement st2=con1.createStatement( );
                          
                       ResultSet rs=st2.executeQuery("select stime,etime from  schedule2 where idno="+idn+" and dd="+d+" ");
                       while(rs.next())
                       {
                         stim=rs.getTime("stime");
                         etim=rs.getTime("etime");
                         int i=stim.compareTo(sti);
                         int j=sti.compareTo(etim);
                         int a=stim.compareTo(eti);
                         int b=eti.compareTo(etim);
                        
                         if( (i==0)&&(b==0) )
                         {
                          check1=true;
                         }
                         
                         if( ((i==-1) && (j==-1))||((a==-1)&&(b==0)) )
                           {
                            check1=true;
                           }
                                                   
                       }

                     con1.close();
                     }
                     catch(Exception ec)
                     {
                           System.out.println(""+ec);
                     }

                    if(check1)
                    {
                        Mcheck mt=new Mcheck(this,"This time allready scheduled");
                    }
                    else
                    {
                     value=1;
                    }

           }*/
 /*public static void main(String s55[])
   {
   new details();
   }*/


   }


