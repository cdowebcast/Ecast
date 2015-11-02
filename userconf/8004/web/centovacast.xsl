<xsl:stylesheet xmlns:xsl = "http://www.w3.org/1999/XSL/Transform" version = "1.0" >
<xsl:output omit-xml-declaration="no" method="xml" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" indent="yes" encoding="UTF-8" />
<xsl:template match = "/icestats" >
<pre>
MountPoint|Connections|Stream Name|Genre|Current Listeners|Description|Bitrate|Stream URL|Currently Playing| 
Global|Client:<xsl:value-of select="connections" /> Source: <xsl:value-of select="source_connections" />||<xsl:value-of select="listeners" />||
<xsl:for-each select="source">
<xsl:value-of select="@mount" />||<xsl:value-of select="server_name" />|<xsl:value-of select="genre" />|<xsl:value-of select="listeners" />|<xsl:value-of select="server_description" />|<xsl:value-of select="bitrate" />~<xsl:value-of select="ice-bitrate" />~<xsl:value-of select="audio_bitrate" />|<xsl:value-of select="server_url" />|<xsl:value-of select="artist" /> - <xsl:value-of select="title" />| 
</xsl:for-each>
</pre>
</xsl:template>
</xsl:stylesheet>
