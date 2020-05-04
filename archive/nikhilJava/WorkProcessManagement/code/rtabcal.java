import java.awt.*;
import java.awt.color.*;
import java.awt.event.*;
import javax.swing.*;
import javax.swing.event.*;
import javax.swing.border.*;
import javax.swing.table.*;
import java.util.*;
import java.util.Calendar;
import java.sql.*;

public class rtabcal extends JFrame
{
       JTabbedPane jtp;
       ViewPanel view;
       Calendar cal=Calendar.getInstance();
       JPanel p1;

       public rtabcal()
       {
         setSize(675,550);
         setTitle("Calendar");
         view = new ViewPanel();
         jtp  = new JTabbedPane();

         jtp.addTab("VIEW-SCHEDULE",view);
         
         getContentPane().add(jtp);

         setVisible(true);
       }

       public  void  sname(String ss,int id1)
       {
         view.vname(ss,id1); 
       }

     /*public static void main(String a[])
       {
         new rtabcal();
       }*/ 
        
 public class ViewPanel extends JPanel implements ActionListener
 {
   details dta;
   JPanel pp,pp1;
   String oname=null;
   String border_type="Titled";
   AbstractBorder border=new TitledBorder("             ");
   JPopupMenu pm;

   int date,i,j,id4=0,nod=0,m1,y1;

    String[] years={ "1970","1971","1972","1973","1974","1975","1976",
                  "1977","1978","1979","1980","1981","1982","1983",
                  "1984","1985","1986","1987","1988","1989","1990",
                  "1991","1992","1993","1994","1995","1996","1997",
                  "1998","1999","2000","2001","2002","2003","2004",
                  "2005","2006","2007","2008","2009","2010","2011"};

   JComboBox comboBox=new JComboBox(years);

   String[] months={ "JAN","FEB","MAR",
                     "APR","MAY","JUN",
                      "JULY","AUG","SEP",
                      "OCT","NOV","DEC" };

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

 public void buildGUI()
 {
        LayoutComponents();
 }

 public void LayoutComponents()
 {
        detl=new JLabel("Details");
        detl.setForeground(Color.black);
        detl.setBounds(250,269,50,20);
        current_month=cal.get(Calendar.MONTH);
        current_year=cal.get(Calendar.YEAR);
        pp=new JPanel();
        setLayout(null);
        pp.setLayout(null);
        pp.setBounds(235,65,410,191);
        pp.setBackground(Color.white);

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
        text =new JLabel("Enter The Dates In The"+
                         " Fields To Get The Activities");
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
        model.setMonth(comboBox.getSelectedIndex()+1970,
        list.getSelectedIndex());
        add(uname);add(lname);add(detl);
        add(comboBox);add(scrollPane);add(pp);
        add(text);add(stdl);add(enddl);
        add(stdf);add(enddf);add(week);
        add(month);add(monl);

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
                 String eq=" select stime,etime,desc from schedule2"+
                           " where idno="+id4+" and dd="+date+" and "+
                           " mm="+m1+" and yy="+y1+"";
                 rs=st.executeQuery(eq);
                 while(rs.next())
                 {
                  ds=rs.getString("desc");
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
                  jmenuItem[k1].addActionListener(new MyAdapter1(k1)
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
       }
     }

     public class ListHandler implements ListSelectionListener
     {
       public void valueChanged(ListSelectionEvent e)
       {
         model.setMonth(comboBox.getSelectedIndex() +1970,
         list.getSelectedIndex());
         table.repaint();
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
         model.setMonth(comboBox.getSelectedIndex()+1970,
         list.getSelectedIndex());
         table.repaint();
       }
     }
   }
 }
  
 class MyAdapter1 implements ActionListener
 {
   int i;
   public MyAdapter1(int k)
   {
      i=k;
   }
 public void actionPerformed(ActionEvent ae)
 {
 }
}
