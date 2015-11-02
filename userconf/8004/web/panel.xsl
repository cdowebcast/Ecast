<xsl:stylesheet xmlns:xsl = "http://www.w3.org/1999/XSL/Transform" version = "1.0" >
<xsl:output omit-xml-declaration="no" method="xml" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" indent="yes" encoding="UTF-8" />
<xsl:template match = "/icestats" >
<pre>
<xsl:value-of select="connections" /> Source: <xsl:value-of select="source_connections" />,,<xsl:value-of select="listeners" />,,
<xsl:for-each select="source">
<xsl:value-of select="@mount" />,,<xsl:value-of select="name" /><bitrate><xsl:value-of select="bitrate" /></bitrate><oyentes><xsl:value-of select="listeners" /></oyentes><picooyentes><xsl:value-of select="listener_peak" /></picooyentes><cancion><xsl:if test="artist"><xsl:value-of select="artist" /> - </xsl:if><xsl:value-of select="title" /></cancion>
</xsl:for-each>
</pre>
</xsl:template>
</xsl:stylesheet>