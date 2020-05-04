import java.applet.*;
import java.awt.*;

/*<applet code =img.class height=300 width=300>
<param name="im" value="calendaring_icon.gif">
</applet>*/

public class img extends Applet
{
Image im;
  public void init()
  {
    im=getImage(getDocumentBase(),getParameter("im"));
  }

public void paint(Graphics g)
{
 g.drawImage(im,10,10,this);
}
}
