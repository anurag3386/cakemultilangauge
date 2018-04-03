<?php /* -*- mode: html; -*- */ ?>
<?php
$orderId = $_REQUEST['orderId'];
$order_value = sprintf("Kr.%d DKK", $orderId);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Horoskop analyser</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link href="http://www.world-of-wisdom.com/styles/masterstyle.css" rel="stylesheet" type="text/css" />
    <link href="../styles/orders.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" language="JavaScript">
      <!--
	  function AboutSecurity() {
	  side = "http://worldofwisdom.dk/shop/Paygateway-dandomain-security.asp";
	  /* side = "http://www.world-of-wisdom.com/wsapi/payment/pbs/security.html"; */
	  width = 550;
	  height= 500;
	  var left = (screen.width-width)/2;
	  var top = (screen.height-height)/2;
	  window.open(side,"Sikkerhed","left="+left+",top="+top+",screenX="+left+",screenY="+top+
          ",width="+width+",height="+height+",scrollbars=yes,resizable=no");
	  }
	  
	  function AboutCVC() {
	  side = "http://worldofwisdom.dk/shop/Paygateway-dandomain-cvc.asp";
	  /* side = "http://www.world-of-wisdom.com/wsapi/payment/pbs/cvc.html"; */
	  width = 550;
	  height= 420;
	  var left = (screen.width-width)/2;
	  var top = (screen.height-height)/2;
	  window.open(side,"Sikkerhed","left="+left+",top="+top+",screenX="+left+",screenY="+top+
          ",width="+width+",height="+height+",scrollbars=yes,resizable=no");
	  }
	  //-->
    </script>
  </head>
  <body>
    <div id="wrap">
            <div id="sky">
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="760" height="70">
	  <param name="movie" value="../sky.swf" />
	  <param name="quality" value="high" />
	  <param name="BGCOLOR" value="#FFFFFF" />
	  <param name="LOOP" value="false" />
	  <param name="PLAY" value="false" />
	  <embed src="../sky.swf" width="760" height="70" loop="False" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" bgcolor="#FFFFFF" play="false"></embed>
	</object>
      </div>
      <div id="menu">
	<table border="0" cellspacing="0">
	  <tr>
	    <td>
	      <a href="../01_horoscopes/horoscopes.htm" target="_self">
		<img src="../global_assets/01_horoscopes.gif" alt="" width="152" height="25" border="0" />
	      </a>
	    </td>
	    <td>
	      <a href="../02_software/software.htm" target="_self">
		<img src="../global_assets/02_software.gif" alt="" width="152" height="25" border="0" />
	      </a>
	    </td>
	    <td>
	      <a href="reports.htm" target="_self">
		<img src="../global_assets/03_reports.gif" alt="" width="152" height="25" border="0" />
	      </a>
	    </td>
	    <td>
	      <a href="../04_articles/articles.htm" target="_self">
		<img src="../global_assets/04_articles.gif" alt="" width="152" height="25" border="0" />
	      </a>
	    </td>
	    <td>
	      <a href="../05_astrologers/astrologers.htm" target="_self">
		<img src="../global_assets/05_astrologers.gif" alt="" width="152" height="25" border="0" />
	      </a>
	    </td>
	  </tr>
	</table>
      </div>
      <div id="shadow"></div>
      <div id="title">
	<img src="img/hor_analys_tx.gif" alt="" width="144" height="19" />
      </div>
      <div id="crumbs">
	<table width="505" border="0" cellpadding="0" cellspacing="0" >
	  <tr>
	    <td>
	      <a href="../index2.htm" target="_self">home</a> | 
	      <strong>
		<a href="reports.htm" target="_self">horoskop analyser </a> | Aarstidshoroskop fra WOW 
	      </strong>
	    </td>
	  </tr>
	</table>
      </div>

      
      <div id="leftborder">
	<img src="../global_assets/dotted_left.gif" alt="" width="235" height="10" />
      </div>
      <div id="left">
	<table border="0" cellspacing="0" class="leftheader">
	  <tr>
	    <td class="green"><img src="img/reports_icon.gif" alt="" width="25" height="25" /></td>
	    <td class="leftbar"><img src="img/reports_txt.gif" alt="" width="116" height="25" /></td>
	  </tr>
	</table>
	<table border="0" cellspacing="0" class="leftcontent2">
	  <tr><td><a href="personlig_analyse.htm" target="_self">Personligt horoskop - WOW</a></td></tr>
	  <tr><td><a href="parforholds_analyse.htm" target="_self">Parforholdsanalyse - WOW </a></td></tr>
	  <tr><td><a href="aarstid_analyse.htm" target="_self">Aarstidshoroskop - WOW</a></td></tr>
	  <tr><td><a href="lz_personal.htm" target="_self">Psykologisk horoskop - Liz Greene</a></td></tr>
	  <tr><td><a href="lz_karriere.htm" target="_self">Karriere og erhverv - Liz Greene</a></td></tr>
	  <tr><td><a href="lz_barn.htm" target="_self">B&oslash;rnehoroskop - Liz Greene</a></td></tr>
	  <tr><td><a href="lz_par.htm" target="_self">Samlivshoroskop - Liz Greene</a></td></tr>
	  <tr><td><a href="lz_kalendar.htm" target="_self"> Horoskopkalender - Robert Hand </a></td></tr>
	</table>
	<table border="0" cellspacing="0" class="leftheader">
	  <tr>
	    <td class="green"><img src="img/konsult_icon.gif" alt="" width="25" height="25" /></td>
	    <td class="leftbar"><img src="img/konsult_txt.gif" alt="" width="94" height="25" /></td>
	  </tr>
	</table>
	<table border="0" cellspacing="0" class="leftcontent2">
	  <tr><td><strong><a href="konsult_adrian.htm" target="_self">Konsultation med Adrian </a></strong></td></tr>
	  <tr><td><a href="konsultation.htm" target="_self">Konsultation med Charlotte</a></td></tr>
	  <tr><td><a href="online_konsult.htm" target="_self">E-mail konsultationer</a></td></tr>
	</table>
      </div>

      <div id="right">
	<table border="0" cellspacing="0" class="rightheader">
	  <tr>
	    <td class="green"><img src="img/reports_icon.gif" alt="" width="25" height="25" /></td>
	    <td class="rightbar"><img src="img/reports_txt.gif" alt="" width="116" height="25" /></td>
	  </tr>
	</table>
	<table border="0" cellspacing="0">
	  <tr>
	    <th>Indtast kreditkort nummer og udl&oslash;bs m&aring;ned/&aring;r</tk>
	  </tr>
	  <tr>
	    <td class="bodytext">
	      <div id="orderform">
		<form
		   method="POST"
		   action="http://www.world-of-wisdom.com/06_affiliates/wowdk/pbs-collect.php"
		   name="myform" id="frmHIOrder"
		   autocomplete="off"
		   >
		  <!-- order dpecific order id# -->
		  <input type="hidden" name="OrderID" value="<?php echo $orderId; ?>"/>
		  <!-- currency and amount DKK=208 -->
		  <!--input type="hidden" name="CurrencyID" value="208"/-->
		  <!--input type="hidden" name="Amount" value="195,00"/-->
		  <!-- merchant number -->
		  <!--input type="hidden" name="MerchantNumber" value="3012689"/-->
		  <!-- visible table details -->
		  <table width="400" border='0' cellspacing='0' cellpadding='0'>
		    <tr>
		      <td>
			<table border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td><label>Bel&oslash;b der h&aelig;ves</label></td>
			    <td><?php echo $order_value; ?></td>
			  </tr>
			  <tr>
			    <td><label for="CardNumber">Kortnummer</label></td>
			    <td>
			      <select name="CardNumber">
				<option value="1111111111111111">Valid Card</option>
				<option value="2222222222222222">Invalid Card</option>
			      </select>
			    </td>
			  </tr>
			  <tr>
			    <td><label for="CardCVC">Kontrolcifre</label></td>
			    <td>
			      <input type="text" name="CardCVC" maxlength="3" size="3" value="123"/>&nbsp;<a href="javascript:AboutCVC()">
				<img src="https://pay.dandomain.dk/images/help.gif"  border="0"/>
			      </a>
			    </td>
			  </tr>
			  <tr>
			    <td><label>Udl&oslash;bs m&aring;ned/&aring;r</label></td>
			    <td>
			      <select name="ExpireMonth">
				<option>01</option>
				<option>02</option>
				<option>03</option>
				<option>04</option>
				<option>05</option>
				<option>06</option>
				<option>07</option>
				<option>08</option>
				<option>09</option>
				<option>10</option>
				<option>11</option>
				<option>12</option>
			      </select>
			      <strong>&nbsp;/&nbsp;</strong>
			      <select name="ExpireYear">
				<option>05</option>
				<option>06</option>
				<option>07</option>
				<option>08</option>
				<option>09</option>
				<option>10</option>
				<option>11</option>
				<option>12</option>
				<option>13</option>
				<option>14</option>
				<option>15</option>
				<option>16</option>
				<option>17</option>
				<option>18</option>
				<option>19</option>
				<option>20</option>
			      </select>
			    </td>
			  </tr>
			  <tr>
			    <td><label>Mode</label></td>
			    <td>
			      <select name="TestMode">
				<option value="1">Test</option>
				<option value="0">Production</option>
			      </select>
			    </td>
			  </tr>
			  <tr>
			    <td>&nbsp;</td>
			    <td>
			      <input type="submit" name="submit" value="Godkend"/>
			    </td>
			  </tr>
			  <tr>
			    <td valign="middle" colspan="2">
			      <a href="javascript:AboutSecurity()">
				<img src="https://pay.dandomain.dk/images/secure.gif" border="0"/>
				&nbsp;Om sikkerheden
			      </a>
			    </td>
			  </tr>
			  <tr>
			    <td colspan="2">&nbsp;</td>
			  </tr>
			</table>
		      </td>
		    </tr>
		  </table>
		</form>
	      </div>
	    </td>
	  </tr>
	  <tr>
	    <td class="bodytext">
	      <p>
		<img src="../images/credit_dd.jpg" width="200" height="70" />
		<br />
	      </p>
	    </td>
	  </tr>
	</table>
	
        <table cellspacing="0">
	  <tr>
	    <td class="bodytext">
	      <a href="../index2.htm">Home</a> |
	      <a href="../01_horoscopes/horoscopes.htm">Stjernetegn</a> |
	      <a href="../02_software/software.htm">Astrologi Software</a> |
	      <a href="../03_replorts/reports.htm">Horoskop Analyser</a> |
	      <a href="../04_articles/articles.htm">Artikler</a> |
	      <a href="../05_astrologers/astrologers.htm">Astrologerne</a></td>
	  </tr>
	</table>

      </div>
    </div>
    
    <!-- Traceworks.com start -->
    <script language='javascript' src='http://stl.p.a1.traceworks.com/prod/reg_scripts/reg_75118-1.js'></script>
    <!-- Traceworks.com end -->

    
    <!-- jQuery -->
    <script type="text/javascript" language="javascript" src="/Scripts/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" language="javascript" src="/Scripts/jquery-autocomplete.plugin.js"></script>
    <!-- local development -->
    <script type="text/javascript" language="javascript" src="http://www.world-of-wisdom.com/Scripts/jquery-wowdk.js"></script>

  </body>
</html>
