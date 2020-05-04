
 import javax.swing.*;
import javax.swing.event.*;
import javax.swing.table.*;
import javax.swing.border.*;
import java.awt.*;
import java.awt.color.*;
import java.awt.event.*;
import java.awt.image.*;
import java.util.*;
import java.util.Date;
import java.util.Calendar;
import java.applet.*;
import java.sql.*;

public class tabcal extends JFrame
{

       JTabbedPane jtp;
       AddPanel add;
       ViewPanel view;
       Calendar cal=Calendar.getInstance();
       JPanel p1;

       public tabcal()
       {
         setSize(800,575);
         setTitle("Calendar");
         add  = new AddPanel();
         view = new ViewPanel();
         jtp  = new JTabbedPane();
         jtp.addTab("SCHEDULE",add);
         jtp.addTab("VIEW-SCHEDULE",view);
         getContentPane().add(jtp);
         getContentPane().setBackground(Color.black);
         setVisible(true);
       }

       public  void  sname(String ss,int id1)
       {
         add.rname(ss,id1);
         view.vname(ss,id1); 
       }

     /*public static void main(String a[])
       {
         new tabcal();
       }*/ 


public class AddPanel extends JPanel implements ActionListener,ItemListener
{
 JCheckBox calle;
 JPanel pc;
 JPopupMenu pm;
 JTextArea dtarea;TextArea tarea;
 JTextField ta;
 JPanel p2,p3,p4,pp;
 JComboBox shcb,ehcb,smcb,emcb;
 JLabel tl,tle,desl,pl,dl,ml,yl,rrh1,rrm1,rrh2,rrm2,detail,descnol;
 JLabel datel; JCheckBox jmenuItem[];int idnumber[];
 JComboBox pcb;                      int uidno[];
 JTextField descnot; 
 JLabel mml,mml1;
 String mon=null;
 String border_type="Line";
 AbstractBorder border=new LineBorder(Color.black,1);
 int idn=0;
 int date=0;
 int current_year,current_month;

String[] years={ "1970","1971","1972","1973","1974","1975","1976",
                  "1977","1978","1979","1980","1981","1982","1983",
                  "1984","1985","1986","1987","1988","1989","1990",
                  "1991","1992","1993","1994","1995","1996","1997",
                  "1998","1999","2000","2001","2002","2003","2004",
                  "2005","2006","2007","2008","2009","2010","2011"};


 JComboBox comboBox=new JComboBox(years);

 String[] months={"JAN","FEB","MAR",
                  "APR","MAY","JUN",
                  "JULY","AUG","SEP",
                  "OCT","NOV","DEC"};

 JList list = new JList(months);
 JScrollPane scrollPane =new JScrollPane(list);

 String[] hours={ "1","2","3","4","5","6",
                  "7","8","9","10","11","12",
                  "13","14","15","16","17","18",
                  "19","20","21","22","23"};

 String[] minits={ "0","5","10","15","20",
                   "25","30","35","40",
                   "45","50","55"};
 
 CalendarModel model = new CalendarModel();
 CalendarModel model1= new CalendarModel();

 JLabel uname;
 JLabel jlb;
 JButton sub,check;
 String sname=null;
 JScrollPane spane;
 JTable table = new JTable(model);
 JTable table1 =new JTable(model1);


    public AddPanel()
    {
        buildGUI();
    }

    public void rname(String se,int id2)
    {
        idn=id2;
        sname=se;
        jlb.setText(""+se);
        jlb.setForeground(Color.black);
    }
    
  public void checkbox()
  {
                    String name=null;
                    int unum=0;
                    ResultSet rs;
                    Vector vm=new Vector(10,10);
                    Vector vm1=new Vector(10,10);
                    try
                      {   
                           pc.removeAll();
                           int jj=0;
                           Class cfn=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                           Connection con=DriverManager.getConnection("Jdbc:Odbc:pro");
                           Statement st=con.createStatement();
                           rs=st.executeQuery("select * from regs");
                           while(rs.next())
                           {
                             name=rs.getString("l_name");
                             unum=rs.getInt("idno");
                             vm.addElement(new JCheckBox(name));
                             vm1.addElement(""+unum);
                             pc.setLayout(new GridLayout(vm.size(),1));
                           }
                             con.close();
                      }
                      
                          catch(Exception ec)
                          {
                           System.out.println(""+ec);
                          }

                           Enumeration enum=vm.elements();
                           Enumeration enum1=vm1.elements();
                           int k=0;int k1=0;
                           jmenuItem = new JCheckBox[vm.size()];
                           idnumber  =new int[vm1.size()];
                           while(enum.hasMoreElements())
                           {
                             jmenuItem[k]=(JCheckBox)enum.nextElement();
                             pc.add(jmenuItem[k]);
                             k++;
                           }
                           while(enum1.hasMoreElements())
                           {
                             idnumber[k1]=Integer.parseInt((String)enum1.nextElement());
                             k1++;
                           }


       }
      
    public void itemStateChanged(ItemEvent ie)
    {
      if(ie.getSource()==calle)
      {             
         for(int i=0;i<jmenuItem.length;i++)
         {
          jmenuItem[i].setSelected(true);
         }  
      } 
    }
    
    public void actionPerformed(ActionEvent ae)
    {   Mcheck mbox3;
        ResultSet rs;
        boolean check1=false;int ch=0;
        int sh=0,sm=0,eh=0,em=0;
        Time stim,etim;

                      sh=Integer.parseInt((String)shcb.getSelectedItem());
                      sm=Integer.parseInt((String)smcb.getSelectedItem());
                      eh=Integer.parseInt((String)ehcb.getSelectedItem());
                      em=Integer.parseInt((String)emcb.getSelectedItem());
                      Time sti=new Time(sh,sm,00);
                      Time et=new Time(eh,em,00);

            if(ae.getSource()==check)
             {
                   boolean select=false;
                   for(int i=0;i<jmenuItem.length;i++)
                   { 
                     if(jmenuItem[i].isSelected())
                     {
                     select=true;
                     }
                   }
                if(select)
                 {
                   Vector vc=new Vector(6,6);
                   for(int i=0;i<jmenuItem.length;i++)
                   { 
                     if(jmenuItem[i].isSelected())
                     {
                      vc.addElement(""+idnumber[i]);
                     }
                   }

                     Enumeration enu=vc.elements();
                     int l=0;
                     uidno = new int[vc.size()];
                     while(enu.hasMoreElements())
                     {
                      uidno[l]=Integer.parseInt((String)enu.nextElement());
                      l++;
                     }
                     System.out.println("kkkkkkk");
                     for(int i=0;i<uidno.length;i++)
                     {
                      System.out.println(""+uidno[i]);
                     }
                     System.out.println("ssssssss");

                    for(int k=0;k<uidno.length; k++)
                    {
                     try
                       {
                         Class cfn=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                         Connection con=DriverManager.getConnection("Jdbc:Odbc:pro");
                         Statement st=con.createStatement( );
                         System.out.println(""+uidno[k]);
                         System.out.println("*************");
                         rs=st.executeQuery("select stime,etime from  schedule2 where idno="+uidno[k]+" and dd="+date+" ");
                         while(rs.next())
                         {

                        stim =rs.getTime("stime");
                        etim =rs.getTime("etime");
                        int i=stim.compareTo(sti);
                        int j=sti.compareTo(etim);
                        int a=stim.compareTo(et);
                        int b=et.compareTo(etim);

                        int l1=sti.compareTo(stim);
                        int m=stim.compareTo(et);
                        if( (i==0)||(b==0) )
                         {
                         check1=true;
                         }
                        
                        
                        if( (i==0)&&(b==0) )
                         {
                         check1=true;
                         }
                         if( (l1==-1)&&(m==-1) )
                         {
                          check1=true;
                         }
                         
                         if( ((i==-1) && (j==-1))||((a==-1)&&(b==-1)) )
                           {
                            check1=true;
                           }



















                        /*   stim=rs.getTime("stime");
                           etim=rs.getTime("etime");
                           int i=stim.compareTo(sti);
                           int j=sti.compareTo(etim);
                           int a=stim.compareTo(et);
                           int b=et.compareTo(etim);
                          //System.out.println("i="+i+"j="+j+"a="+a+"b="+b);
                        
                           if( (i==0)&&(b==0) )
                           {
                            check1=true;
                           }
                         
                           if( ((i==-1) && (j==-1))||((a==-1)&&(b==0)) )
                           {
                             check1=true;
                           } */

                      }

                       con.close();
                     }
                     catch(Exception ec)
                     {
                           System.out.println(""+ec);
                     }


             }


                if(check1)
                {
                Mcheck mc1=new Mcheck(tabcal.this,"N o t  F r e e");
                }
                else
                {
                Mcheck mc1=new Mcheck(tabcal.this,"F r e e");
                }
             }
             else
             {
             Mcheck mc1=new Mcheck(tabcal.this,"You have to select Employee"); 
             }

             }

             if(ae.getSource()==sub)
             {  

                try
                   {
                      Class cfn=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                      Connection con=DriverManager.getConnection("Jdbc:Odbc:pro");
                      Statement st=con.createStatement( );
                          
                      rs=st.executeQuery("select stime,etime from  schedule2 where idno="+idn+" and dd="+date+" ");
                      while(rs.next())
                      {
                        stim =rs.getTime("stime");
                        etim =rs.getTime("etime");
                        int i=stim.compareTo(sti);
                        int j=sti.compareTo(etim);
                        int a=stim.compareTo(et);
                        int b=et.compareTo(etim);

                        int l=sti.compareTo(stim);
                        int m=stim.compareTo(et);
                        if( (i==0)||(b==0) )
                         {
                         check1=true;
                         }
                        
                        
                        if( (i==0)&&(b==0) )
                         {
                         check1=true;
                         }
                         if( (l==-1)&&(m==-1) )
                         {
                          check1=true;
                         }
                         
                         if( ((i==-1) && (j==-1))||((a==-1)&&(b==-1)) )
                           {
                            check1=true;
                           }
                                                   
                       }

                     con.close();
                     }
                     catch(Exception ec)
                     {
                           System.out.println(""+ec);
                     }

                    if(check1)
                    {
                        Mcheck mt=new Mcheck(tabcal.this,"Time already scheduled...");
                    }
                    else
                    {
                        try
                        {

                          int m1=(int)list.getSelectedIndex();
                          String yyy=(String)comboBox.getSelectedItem();
                          m1=m1+1;
                          int y1=Integer.parseInt(yyy);
                          dl.setText(""+date);
                          mon=(String)list.getSelectedValue();
                          sh=Integer.parseInt((String)shcb.getSelectedItem());
                          eh=Integer.parseInt((String)ehcb.getSelectedItem());
                          sm=Integer.parseInt((String)smcb.getSelectedItem());
                          em=Integer.parseInt((String)emcb.getSelectedItem());
                          sti=new Time(sh,sm,00);
                          et=new Time(eh,em,00);
                          Class cfn=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                          Connection con=DriverManager.getConnection("Jdbc:Odbc:pro");
                          Statement st=con.createStatement( );
                          String rst=(String)pcb.getSelectedItem();
                          int descno=Integer.parseInt(descnot.getText());
                          String fback="Yet to Attend";
                          int x1=st.executeUpdate("insert into schedule2  values("+idn+","+date+","+m1+","+y1+",'"+sti+"','"+et+"','"+rst+"',"+descno+",'"+ta.getText()+"','"+fback+"','"+dtarea.getText()+"')");
                           mbox3=new Mcheck(tabcal.this,"Time is been scheduled...");

                           for(int k=0;k<uidno.length; k++)
                           {
                             if(idn!=uidno[k])
                             {
                              int x2=st.executeUpdate("insert into schedule2  values("+uidno[k]+","+date+","+m1+","+y1+",'"+sti+"','"+et+"','"+rst+"',"+descno+",'"+ta.getText()+"','"+fback+"','"+dtarea.getText()+"')");
                             }
                           }


                           ta.setText(" ");
                           dtarea.setText("  ");
                           descnot.setText("  ");

                           mbox3=new Mcheck(tabcal.this,"Time is been scheduled...");

                          con.close();
                        }
                        catch(Exception ec)
                        {
                          System.out.println(""+ec);
                        }
                    }
           
               }
    }
              
    public void buildGUI()
    {
     LayoutComponents();
    }


    public void LayoutComponents()
    {

        calle=new JCheckBox("Call Every One");
        pm=new JPopupMenu();
        pc=new JPanel();
        pp=new JPanel();
        spane=new JScrollPane(pc);
        datel=new JLabel();
        tarea=new TextArea(30,30);
        current_year=cal.get(Calendar.YEAR);
        current_month=cal.get(Calendar.MONTH);
        p2=new JPanel();
        p3=new JPanel();
        p2.setLayout(null);
        p3.setLayout(null);
        p2.setBounds(90,80,210,190);
        p3.setBounds(340,80,210,190);
        p2.setBackground(Color.white);
        p3.setBackground(Color.white);
        datel.setBounds(590,55,200,20);
        datel.setForeground(Color.black);
        tarea.setBounds(567,85,200,185);
        spane.setBounds(590,285,130,130);
        calle.setBounds(575,440,115,20);
        tarea.setEditable(false);
        setLayout(null);
        p4=new JPanel();
        p4.setBounds(50,275,500,200);
        p4.setBackground(Color.gray);
        p4.setLayout(null);
        setLayout(null);

        shcb  =new JComboBox(hours);
        ehcb  =new JComboBox(hours);
        smcb  =new JComboBox(minits);
        emcb  =new JComboBox(minits);

        ta    =new JTextField(100);
        dtarea=new JTextArea(20,8);
        dtarea.setVisible(false);
        tl    =new JLabel("Start Time");
        tle   =new JLabel("End Time");
        desl  =new JLabel("Description");
        detail=new JLabel("Details");
        descnol=new JLabel("Desc..No");
        descnot=new JTextField(10);
        tl.setForeground(Color.black);
        tle.setForeground(Color.black);
        desl.setForeground(Color.black);
        detail.setForeground(Color.black);
        descnol.setForeground(Color.black);
        dl  =new JLabel();
        ml  =new JLabel();
        yl  =new JLabel();

        rrh1 =new JLabel("Hr");
        rrm1 =new JLabel("Min");
        rrh2 =new JLabel("Hr");
        rrm2 =new JLabel("Min");
        rrh1.setForeground(Color.black);
        rrm1.setForeground(Color.black);
        rrh2.setForeground(Color.black);
        rrm2.setForeground(Color.black);
        mml   =new JLabel();
        mml1 =new JLabel();
        pl  =new JLabel("PREFERENCE");
        pl.setForeground(Color.black);
        pcb =new JComboBox();
        pcb.addItem("Compulsory");
        pcb.addItem("Better To Attend");
        pcb.addItem("General");
        sub = new JButton("Submit");
        check = new JButton("Check");
        check.setBounds(693,440,70,20);
        sub.setForeground(Color.black);
        uname=new JLabel("NAME");
        jlb  =new JLabel();
        uname.setBounds(10,10,100,20);
        uname.setForeground(Color.black);
        mml.setBounds(175,55,50,20);
        mml1.setBounds(425,55,50,20);
        mml.setForeground(Color.black);
        mml1.setForeground(Color.black);
        jlb.setBounds(60,10,100,20);
        dl.setBounds(62,272,30,20);
        ml.setBounds(82,272,30,20);
        yl.setBounds(112,272,30,20);
        dl.setForeground(Color.black);
        ml.setForeground(Color.black);
        yl.setForeground(Color.black);
        tl.setBounds(76,315,100,20);
        tle.setBounds(260,315,100,20);
        desl.setBounds(70,355,80,20);
        detail.setBounds(70,385,70,20);
        descnol.setBounds(380,355,80,20);
        descnot.setBounds(440,355,40,20);
        rrh1.setBounds(156,295,50,20);
        rrm1.setBounds(206,295,50,20);
        rrh2.setBounds(333,295,50,20);
        rrm2.setBounds(376,295,50,20);
        shcb.setBounds(150,315,50,20);
        smcb.setBounds(200,315,50,20);
        ehcb.setBounds(328,315,50,20);
        emcb.setBounds(376,315,50,20);

        ta.setBounds(150,355,200,25);
        dtarea.setBounds(150,385,250,50);
        sub.setBounds(333,440,75,20);
        pl.setBounds(70,440,100,20);
        pcb.setBounds(156,440,120,20);
        add(rrh1);add(rrm1);add(rrh2);add(rrm2);
        add(sub);add(spane);
        add(tl);add(desl);add(detail);add(mml);add(mml1);
        add(shcb);add(smcb);add(ta);add(dtarea);add(ehcb);
        add(emcb);add(tle);add(pl);add(pcb);add(check);
        add(descnol);add(descnot);
        comboBox.setBounds(110,18,66,17);
        for(int k=0;k<=years.length;k++)
        {
            if(String.valueOf(current_year).equals(years[k]))
            {
             comboBox.setSelectedIndex(k);break;
            }
        }
        comboBox.addItemListener(new ComboHandler());
        scrollPane.setBounds(190,16,55,40);
        list.setSelectedIndex(current_month);
        list.addListSelectionListener(new ListHandler());

        table.setBounds(5,5,200,180);
        table1.setBounds(5,5,200,180);
        model.setMonth(comboBox.getSelectedIndex()+1970,list.getSelectedIndex());
        model1.setMonth(comboBox.getSelectedIndex()+1970,list.getSelectedIndex()+1);
        add(dl);add(ml);add(yl);
        add(jlb);add(calle);
        add(uname);add(datel);add(tarea);
        add(comboBox);                           
        add(scrollPane);
        add(p2);add(p3);
        table.setRowHeight(21);
        table.setRowMargin(5);
        table.setBackground(Color.gray);
        table.setForeground(Color.white);
        table.setCellSelectionEnabled(true);
        table.setFont(new Font("convecta",Font.BOLD,11));
        table.setShowGrid(false);

        table1.setRowHeight(21);
        table1.setRowMargin(5);
        table1.setBackground(Color.gray);
        table1.setForeground(Color.white);
        table1.setCellSelectionEnabled(true);
        table1.setFont(new Font("convecta",Font.BOLD,11));
        table1.setShowGrid(false);

        sub.setVisible(false);
        check.setVisible(false);
        shcb.setVisible(false);
        smcb.setVisible(false);
        ta.setVisible(false);
        desl.setVisible(false);
        detail.setVisible(false);
        descnol.setVisible(false);
        descnot.setVisible(false);
        tl.setVisible(false);
        spane.setVisible(false);
        tarea.setVisible(false);    
        ehcb.setVisible(false);
        tle.setVisible(false);
        emcb.setVisible(false);
        pl.setVisible(false);
        pcb.setVisible(false);
        rrh1.setVisible(false);
        rrm1.setVisible(false);
        rrh2.setVisible(false);
        rrm2.setVisible(false);
        calle.setVisible(false);
        //calls.setVisible(false);
        
        table.addMouseListener(new MouseAdapter(){
        public void mouseClicked(MouseEvent me)
        {           
                    int  i=me.getX();
                    int  j=me.getY();
                    int r=table.getSelectedRow();
                    int col=table.getSelectedColumn();
                    String sv=(String)table.getValueAt(r,col);
                    date=Integer.parseInt(sv);
                            int m1=(int)list.getSelectedIndex();
                            String yyy=(String)comboBox.getSelectedItem();
                            m1=m1+1;
                            int y1=Integer.parseInt(yyy);
                            dl.setText(""+date);
                            String mon=(String)list.getSelectedValue();
                            ml.setText(mon);
                            yl.setText(""+y1);
                            datel.setText(date+" "+mon+" "+y1+"   Activities");
                    ResultSet rs;
                    String ds=null;
                    
                      try
                      {     tarea.setText("");
                            Class cfn=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                            Connection con=DriverManager.getConnection("Jdbc:Odbc:pro");
                            Statement st=con.createStatement();

                            rs=st.executeQuery("select stime,etime,descr from schedule2  where idno="+idn+" and dd="+date+" and mm="+m1+" and yy="+y1+"");
                            while(rs.next())
                            {
                             ds=rs.getString("descr");
                             tarea.append(rs.getTime("stime")+" to "+rs.getTime("etime")+"      "+ds+"\n");
                            }
                             con.close();
                       }
                          catch(Exception ec)
                          {
                           System.out.println(""+ec);
                          }

                            sub.setVisible(true);
                            check.setVisible(true);
                            shcb.setVisible(true);
                            smcb.setVisible(true);
                            ehcb.setVisible(true);
                            emcb.setVisible(true);
                            calle.setVisible(true);
                            //calls.setVisible(true);
                            spane.setVisible(true);
                            ta.setVisible(true);
                            tarea.setVisible(true);
                            dtarea.setVisible(true);
                            desl.setVisible(true);
                            detail.setVisible(true);
                            descnol.setVisible(true);
                            descnot.setVisible(true);
                            tl.setVisible(true);
                            tle.setVisible(true);
                            pl.setVisible(true);
                            pcb.setVisible(true);
                            rrh1.setVisible(true);
                            rrm1.setVisible(true);
                            rrh2.setVisible(true);
                            rrm2.setVisible(true);

                            if(border_type.equals("Line"))
                            {
                             add(p4);
                             p4.setBorder(border);
                             repaint();
                            }
                            if(border_type.equals("Line"))
                            {
                             add(pp);
                             pp.setBorder(border);
                             pp.setBounds(570,275,200,200);
                             pp.setBackground(Color.gray);
                             repaint();
                            }

                            checkbox();

                    }
                    });

           table1.addMouseListener(new MouseAdapter() {
           public void mouseClicked(MouseEvent me)
           {       
                    int i=me.getX();
                    int j=me.getY();
                    int r=table1.getSelectedRow();
                    int col=table1.getSelectedColumn();
                    String sv=(String)table1.getValueAt(r,col);
                    date=Integer.parseInt(sv);
                            int m1=(int)list.getSelectedIndex();
                            String yyy=(String)comboBox.getSelectedItem();
                            m1=m1+1;
                            int y1=Integer.parseInt(yyy);
                            if(m1>11)
                            { 
                             m1=m1-12;
                             y1=y1+1;
                             dl.setText(""+date);
                             String mon=months[m1];
                             ml.setText(mon);
                             yl.setText(""+y1);
                             datel.setText(date+" "+mon+" "+y1+"   Activities");
                            }

                           else
                           {
                             dl.setText(""+date);
                             String mon=months[m1];
                             ml.setText(mon);
                             yl.setText(""+y1);
                             datel.setText(date+" "+mon+" "+y1+"   Activities");
                           } 

                        ResultSet rs;
                        String ds=null;
                        try
                          {
                                tarea.setText("");
                                Class cfn=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                                Connection con=DriverManager.getConnection("Jdbc:Odbc:pro");
                                Statement st=con.createStatement();
                                rs=st.executeQuery("select stime,etime,descr from schedule2  where idno="+idn+" and dd="+date+" and mm="+m1+" and yy="+y1+"");
                                while(rs.next())
                                {
                                 ds=rs.getString("descr");
                                 tarea.append(rs.getTime("stime")+"to"+rs.getTime("etime")+"      "+ds+"\n");
                                }
                                 con.close();
                          }
                           catch(Exception ec)
                           {
                            System.out.println(""+ec);
                           }

                            sub.setVisible(true);
                            check.setVisible(true);
                            shcb.setVisible(true);
                            smcb.setVisible(true);
                            ehcb.setVisible(true);
                            emcb.setVisible(true);
                            calle.setVisible(true);
                            spane.setVisible(true);
                            ta.setVisible(true);
                            tarea.setVisible(true);
                            dtarea.setVisible(true);
                            desl.setVisible(true);
                            detail.setVisible(true);

                            tl.setVisible(true);
                            tle.setVisible(true);
                            pl.setVisible(true);
                            pcb.setVisible(true);
                            rrh1.setVisible(true);
                            rrm1.setVisible(true);
                            rrh2.setVisible(true);
                            rrm2.setVisible(true);

                            if(border_type.equals("Line"))
                            {
                             add(p4);
                             p4.setBorder(border);
                             repaint();
                            }
                            checkbox();





                           /* spane.setVisible(true);
                            sub.setVisible(true);
                            shcb.setVisible(true);
                            smcb.setVisible(true);
                            ehcb.setVisible(true);
                            emcb.setVisible(true);

                            ta.setVisible(true);
                            desl.setVisible(true);
                            detail.setVisible(true);
                            tl.setVisible(true);
                            tle.setVisible(true);
                            pl.setVisible(true);
                            pcb.setVisible(true);*/


                    }
                    });

         sub.addActionListener(this);
         check.addActionListener(this);
         calle.addItemListener(this);

         p2.add(table);p3.add(table1);

   }

class CalendarModel extends AbstractTableModel
{
        String[] days={"S","M","T","W","T","F","S"};
        int[] numDays={31,28,31,30,31,30,31,31,30,31,30,31};
        String[][] calendar=new String[7][7];

        public CalendarModel()
        {
                for(int i=0;i<days.length;++i)
                calendar[0][i]=days[i];
                for(int i=1;i<7;++i)
                for(int j=0;j<7;j++)
                calendar[i][j]="";
                calendar[1][0]="";
                calendar[5][0]="";
                calendar[6][0]="";
        }

        public int getRowCount()
        {
                return 7;
        }

        public int getColumnCount()
        {
                return 7;
        }

        public Object getValueAt(int row,int column)
        {
                return calendar[row][column];
        }

        public void setValueAt(Object value,int row,int column)
        {
                calendar[row][column]=(String) value;
        }

        public void setMonth(int year,int month)
        {

               for(int i=1;i<7;i++)
               for(int j=1;j<7;j++)
               calendar[i][j]=" ";
               calendar[1][0]="";
               calendar[5][0]="";
               calendar[6][0]="";
               java.util.GregorianCalendar cal=new java.util.GregorianCalendar();
               cal.set(year,month,1);
               int offset=cal.get(java.util.GregorianCalendar.DAY_OF_WEEK)-1;
               offset+=7;
               int num = daysInMonth(year,month);
               for(int i=0;i<num;++i)
               {
                    calendar[offset/7][offset%7]=Integer.toString(i+1);
                    ++offset;
               }
        }

        public boolean isLeapYear(int year)
        {
                if(year % 4==0) return true;
                return false;
        }

        public int daysInMonth(int year,int month)
        {
                int days=numDays[month];
                if (month==1 && isLeapYear(year)) ++days;
                return days;
        }
}
        public class ComboHandler implements ItemListener
        {
                public void itemStateChanged(ItemEvent e)
                {
                        model.setMonth(comboBox.getSelectedIndex() +1970,
                                       list.getSelectedIndex());
                        table.repaint();

                        int m2=(int)list.getSelectedIndex()+1;
                        int y2=(int)comboBox.getSelectedIndex()+1970;

                        if(m2>11)
                        {
                              m2=m2-12;
                              y2=y2+1;
                        }
                        model1.setMonth(y2,m2);
                        table1.repaint();

                }
        }

        public class ListHandler implements ListSelectionListener
        {
                public void valueChanged(ListSelectionEvent e)
                {

                       int m1=(int)list.getSelectedIndex();
                       
                       model.setMonth(comboBox.getSelectedIndex() +1970,
                                      list.getSelectedIndex());
                       String cm=months[m1];
                       mml.setText(cm);
                       table.repaint();


                       int m2=(int)list.getSelectedIndex()+1;
                       int y2=(int)comboBox.getSelectedIndex()+1970;
                       
                       if(m2>11)
                       {
                             m2=m2-12;
                             y2=y2+1;
                       }
                       String mc=months[m2];
                       mml1.setText(mc);
                       model1.setMonth(y2,m2);
                       table1.repaint();



                }
        }

        public class YearMenuItemHandler implements ActionListener
        {
                public void actionPerformed(ActionEvent e)
                {
                       String cmd=e.getActionCommand();
                       int year= (new Integer(cmd)).intValue() - 1970;

                       comboBox.setSelectedIndex(year);
                       model.setMonth(comboBox.getSelectedIndex() +1970,
                                      list.getSelectedIndex());
                       table.repaint();

                       int m2=(int)list.getSelectedIndex()+1;
                       int y2=(int)comboBox.getSelectedIndex()+1970;

                       if(m2>11)
                       {
                             m2=m2-12;
                             y2=y2+1;
                       }
                       model1.setMonth(y2,m2);
                       table1.repaint();
                }
        }
                 
                 
                                 
        public class MonthMenuItemHandler implements ActionListener
        {
                public void actionPerformed(ActionEvent e)
                {
                       String cmd=e.getActionCommand();
                       int month=0;
                       for(int i=0;i<months.length;++i)
                       {
                          if(cmd.equals(months[i]))
                          {
                                month=i;
                                break;
                          }
                       }
                       list.setSelectedIndex(month);
                       model.setMonth(comboBox.getSelectedIndex()+1970,list.getSelectedIndex());
                table.repaint();

                int m2=(int)list.getSelectedIndex()+1;
                int y2=(int)comboBox.getSelectedIndex()+1970;
                if(m2>11)
                {
                  m2=m2-12;
                  y2=y2+1;
                }
                model1.setMonth(y2,m2);
                table1.repaint();


                }
                }

 }
 public class ViewPanel extends JPanel implements ActionListener
 {
   details dta;
   JPanel pp,pp1;
   String oname=null;
   String border_type="Titled";
   AbstractBorder border=new TitledBorder("            ");
   JPopupMenu pm;
   int date,i,j,id4=0,nod=0,m1,y1;
    String[] years={ "1970","1971","1972","1973","1974","1975","1976",
                  "1977","1978","1979","1980","1981","1982","1983",
                  "1984","1985","1986","1987","1988","1989","1990",
                  "1991","1992","1993","1994","1995","1996","1997",
                  "1998","1999","2000","2001","2002","2003","2004",
                  "2005","2006","2007","2008","2009","2010","2011"};
   JComboBox comboBox=new JComboBox(years);
   String[] months={"JAN","FEB","MAR","APR","MAY","JUN","JULY","AUG","SEP","OCT","NOV","DEC"};
   JList list = new JList(months);
   JLabel uname,lname,text,stdl,enddl,monl,detl;
   JTextField stdf,enddf;
   JButton week,month;
   JScrollPane scrollPane =new JScrollPane(list);
   CalendarModel model=new CalendarModel();
   JTable table = new JTable(model);
   JMenuItem[] jmenuItem;
   int current_month,current_year;
   public ViewPanel()
    {
      buildGUI();                 
 
 
    }
 public void actionPerformed(ActionEvent ae2)
 {
        int sm=(int)list.getSelectedIndex();
        String syyy=(String)comboBox.getSelectedItem();
        sm=sm+1;
        
        int sy=Integer.parseInt(syyy);
       if(ae2.getSource()==week)
       {
          int sd=Integer.parseInt(stdf.getText());
          int ed=Integer.parseInt(enddf.getText());
         
        weekly wee=new weekly();
        wee.send(id4,date,sm,sy,sd,ed);
        wee.process();
       }
       else
       if(ae2.getSource()==month)
        {
         if((sm==1)||(sm==3)||(sm==5)||(sm==7)||(sm==8)||(sm==10)||(sm==12))
         {
           nod=31;
           }
         else
         if((sm==4)||(sm==6)||(sm==9)||(sm==11))
         {
           nod=30;
           }
         else
         {
          if((sy % 4==0) && (sm==2))
            {
             nod=29;
             }
          else
          {
           nod=28;
           }
           }

          monthly mee=new monthly();
          mee.send(id4,date,sm,sy,nod);
          mee.process();
        }

 }


 void buildGUI()
 {
 
 LayoutComponents();
 
 }
 public void LayoutComponents()
 {      current_month=cal.get(Calendar.MONTH);
        current_year=cal.get(Calendar.YEAR);
        pp=new JPanel();
        setLayout(null);
        pp.setLayout(null);
        pp.setBounds(235,65,410,191);
        pp.setBackground(Color.white);
        detl=new JLabel("Details");
        detl.setForeground(Color.black);
        detl.setBounds(248,269,50,20);
        pp1=new JPanel();
        setLayout(null);
        pp1.setLayout(null);
        pp1.setBounds(240,270,406,180);
        pp1.setBackground(Color.gray);
        uname=new JLabel("NAME ");
        uname.setForeground(Color.black);
        lname=new JLabel();
        uname.setBounds(40,100,50,20);
        lname.setBounds(100,100,100,20);
        comboBox.setBounds(240,22,70,30);
        for(int k=0;k<years.length;k++)
        {
         if(String.valueOf(current_year).equals(years[k]))
         {
           comboBox.setSelectedIndex(k);break;
         }
        }
        comboBox.addItemListener(new ComboHandler());
        pm=new JPopupMenu();
        scrollPane.setBounds(340,22,60,40);
        list.setSelectedIndex(current_month);
        list.addListSelectionListener(new ListHandler());
        text =new JLabel("Enter The Dates In The Fields To Get The Activities");
        text.setBounds(270,300,300,20);
        text.setForeground(Color.black);
        monl=new JLabel("For Whole Month Activities");
        monl.setForeground(Color.black);
        monl.setBounds(320,383,150,20);
        stdl=new JLabel("Starting Date");
        stdl.setBounds(270,340,80,20);
        stdl.setForeground(Color.black);
        stdf=new JTextField(5);
        stdf.setBounds(350,340,20,20);
        enddl =new JLabel("Ending Date");
        enddl.setBounds(380,340,80,20);
        enddl.setForeground(Color.black);
        enddf=new JTextField(5);
        enddf.setBounds(465,340,20,20);
        week=new JButton("Click");
        week.setBounds(495,337,70,30);
        month=new JButton("Click");
        month.setBounds(495,380,70,30);
        table.setBounds(6,6,400,180);
        pp.add(table);
        model.setMonth(comboBox.getSelectedIndex()+1970,list.getSelectedIndex());
        add(uname);
        add(lname);
        add(comboBox);
        add(scrollPane);add(pp);add(detl);
        add(text);add(stdl);add(enddl);add(stdf);add(enddf);add(week);add(month);add(monl);
        table.setRowHeight(21);
        table.setRowMargin(5);
        table.setBackground(Color.gray);
        table.setForeground(Color.white);
        table.setCellSelectionEnabled(true);
        table.setFont(new Font("convecta",Font.BOLD,17));
        table.setShowGrid(false);

        if(border_type.equals("Titled"))
        {
         add(pp1);
         pp1.setBorder(border);
         repaint();
         pp1.setForeground(Color.black);
        }
        table.addMouseListener(new MouseAdapter(){
        public void mouseClicked(MouseEvent me)
         
           {
                    ResultSet rs;
                    int  i=me.getX();
                    int  j=me.getY();
                    String ds;String  sh2,eh2;
                    int r=table.getSelectedRow();
                    int col=table.getSelectedColumn();
                    String sv=(String)table.getValueAt(r,col);
                     date=Integer.parseInt(sv);
                    Vector vm=new Vector(10,10);
                    
                     try
                      {
                           
                           pm.removeAll();
                           JMenuItem jmi;
                                                         
                           m1=(int)list.getSelectedIndex();
                           String yyy=(String)comboBox.getSelectedItem();
                           m1=m1+1;
                           y1=Integer.parseInt(yyy);
                           Class cfn=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                           Connection con=DriverManager.getConnection("Jdbc:Odbc:pro");
                           Statement st=con.createStatement();
                           rs=st.executeQuery("select stime,etime,descr from schedule2  where idno="+id4+" and dd="+date+" and mm="+m1+" and yy="+y1+"");
                           
                           while(rs.next())
                           {
                            ds=rs.getString("descr");

                            jmi=new JMenuItem(rs.getTime("stime")+"to"+rs.getTime("etime")+":"+ds);
                            pm.add(jmi);
                            vm.addElement(jmi);

                            pm.addSeparator();
                           }
                             table.add(pm);
                             pm.show(table,i,j);
                             con.close();
                                    }
                      
                          catch(Exception ec)
                          {
                           System.out.println(""+ec);
                          }

                           Enumeration enum=vm.elements();
                           int k=0;
                           jmenuItem = new JMenuItem[vm.size()]; 
                           while(enum.hasMoreElements())
                           {
                           jmenuItem[k]=(JMenuItem)enum.nextElement();
                           k++;
                           }
                           for(int k1=0;k1<jmenuItem.length;k1++)
                           {
                              jmenuItem[k1].addActionListener(new MyAdapter(k1)
                                                            {
                                                            
                                                            public void actionPerformed(ActionEvent ae)
                                                            {

                                                             String msg=(String)jmenuItem[this.i].getLabel();
                                                             dta=new details();
                                                             dta.setLocation(130,150);
                                                             dta.senddetails(msg,oname,id4,date,m1,y1);
                                                             dta.process();

                                                            }
                                                            });
                          }

                    }
                    });

        week.addActionListener(this);
        month.addActionListener(this);

 }
 public void vname(String vs,int id3)
 {
  oname=vs;
  id4=id3;
  lname.setText(""+vs);
  lname.setForeground(Color.black);
 }
class CalendarModel extends AbstractTableModel
  {
        String[] days={"SUN","MON","TUE","WED","THU","FRI","SAT"};
        int[] numDays={31,28,31,30,31,30,31,31,30,31,30,31};
        String[][] calendar=new String[7][7];
        
        public CalendarModel()
        {                   

                for(int i=0;i<days.length;++i)
                calendar[0][i]=days[i];
                for(int i=1;i<7;++i)
                for(int j=0;j<7;j++)
                calendar[i][j]= " ";
                calendar[1][0]="";
                calendar[5][0]="";calendar[6][0]="";
        }
        public int getRowCount()
        {
                return 7;
        }
        public int getColumnCount()
        {
                return 7;
        }
        public Object getValueAt(int row,int column)
        {
                return calendar[row][column];
        }
        public void setValueAt(Object value,int row,int column)
        {
                calendar[row][column]=(String) value;
                }
                public void setMonth(int year,int month) {
                for(int i=1;i<7;i++)
                for(int j=1;j<7;j++)
                calendar[i][j]=" ";
                calendar[1][0]="";
                calendar[5][0]="";calendar[6][0]="";
                java.util.GregorianCalendar cal=new java.util.GregorianCalendar();
                cal.set(year,month,1);
                int offset=cal.get(java.util.GregorianCalendar.DAY_OF_WEEK)-1;
                offset+=7;
                int num = daysInMonth(year,month);
                for(int i=0;i<num;++i) {
                calendar[offset/7][offset%7]=Integer.toString(i+1);
                ++offset;
                }
        }
                public boolean isLeapYear(int year) {
                if(year % 4==0) return true;
                return false;
                }
                public int daysInMonth(int year,int month) {
                int days=numDays[month];
                if (month==1 && isLeapYear(year)) ++days;
                return days;
                }
                }

                public class ComboHandler implements ItemListener {
                public void itemStateChanged(ItemEvent e) {
                model.setMonth(comboBox.getSelectedIndex() +1998,list.getSelectedIndex());
                table.repaint();
                }
                }
                public class ListHandler implements ListSelectionListener {
                public void valueChanged(ListSelectionEvent e) {
                model.setMonth(comboBox.getSelectedIndex() +1998,list.getSelectedIndex());
                table.repaint();
                }
                }
                public class YearMenuItemHandler implements ActionListener {
                public void actionPerformed(ActionEvent e) {
                String cmd=e.getActionCommand();
                int year= (new Integer(cmd)).intValue() - 1998;
                comboBox.setSelectedIndex(year);
                model.setMonth(comboBox.getSelectedIndex() +1998,list.getSelectedIndex());
                table.repaint();
                }
               }
                 
                 
                                 
                public class MonthMenuItemHandler implements ActionListener {
                public void actionPerformed(ActionEvent e) {
                String cmd=e.getActionCommand();
                int month=0;
                for(int i=0;i<months.length;++i) {
                if(cmd.equals(months[i])) {
                month=i;
                break;
                }
                }
                list.setSelectedIndex(month);
                model.setMonth(comboBox.getSelectedIndex()+1998,list.getSelectedIndex());
                table.repaint();
                }
                }

 
 }
 }
  
 class MyAdapter implements ActionListener
 {
 int i;
public MyAdapter(int k)
{
i=k;
}
public void actionPerformed(ActionEvent ae)
{
}
}


