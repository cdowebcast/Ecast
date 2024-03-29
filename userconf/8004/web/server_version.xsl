<xsl:stylesheet xmlns:xsl = "http://www.w3.org/1999/XSL/Transform" version = "1.0" >
<xsl:output omit-xml-declaration="no" method="html" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" indent="yes" encoding="UTF-8" />
<xsl:template match = "/icestats" >
<html>
<head>
<title>Icecast Streaming Media Server</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<h2>Server Information</h2>
<br />
<!--index header menu -->
<div class="roundcont">
<div class="roundtop">
<img src="/corner_topleft.jpg" class="corner" style="display: none" />
</div>
<table border="0" width="100%" id="table1" cellspacing="0" cellpadding="4">
	<tr>
		<td bgcolor="#656565">
	    <a class="nav" href="admin/">Administration</a>
		<a class="nav" href="status.xsl">Server Status</a>
		<a class="nav" href="server_version.xsl">Version</a></td>
	</tr>
</table>
<div class="roundbottom">
<img src="/corner_bottomleft.jpg" class="corner" style="display: none" />
</div>
</div>
<br />
<br />
<!--end index header menu -->

<div class="roundcont">
<div class="roundtop">
<img src="/corner_topleft.jpg" class="corner" style="display: none" />
</div>
<div class="newscontent">
<h3>Informacion del Servidor</h3>
<table border="0" cellpadding="4">
<xsl:for-each select="/icestats">
<tr>
	<td width="130">Location</td>
	<td class="streamdata"><xsl:value-of select="location" /></td>
</tr>
<tr>
	<td width="130">Admin</td>
	<td class="streamdata">edin.sof@gmail.com</td>
</tr>
<tr>
    <td width="130">Host</td>
    <td class="streamdata">WWW.HOSTINGPEREIRA.COM</td>
</tr>
<tr>
    <td width="130">Version</td>
    <td class="streamdata"><xsl:value-of select="server_id" /></td>
</tr>
</xsl:for-each>
<tr>
	<td width="130">Download</td>
	<td class="streamdata"><a class="nav" target="_blank" href="http://icecast.org/download.php">icecast.org</a></td>
</tr>
<tr>
	<td width="130">Subversion</td>
	<td class="streamdata"><a class="nav" target="_blank" href="http://icecast.org/svn.php">click here</a></td>
</tr>
<tr>
	<td width="130">Documentation</td>
	<td class="streamdata"><a class="nav" target="_blank" href="http://icecast.org/docs.php">click here</a></td>
</tr>
<tr>
	<td width="130">Stream Directory </td>
	<td class="streamdata"><a class="nav" target="_blank" href="http://dir.xiph.org/index.php">dir.xiph.org</a></td>
</tr>
<tr>
	<td width="130">Community</td>
	<td class="streamdata"><a class="nav" target="_blank" href="http://forum.icecast.org/">forum.icecast.org</a></td>
</tr>
</table>
</div>
<div class="roundbottom">
<img src="/corner_bottomleft.jpg" class="corner" style="display: none" />
</div>
</div>
<br />
<div class="poster">Soluciones de Streaming para RADIO y TV <a class="nav" target="_blank" href="http://www.hostingpereira.com">WWW.HOSTINGPEREIRA.COM</a></div>
</body>
</html>
</xsl:template>
</xsl:stylesheet>
