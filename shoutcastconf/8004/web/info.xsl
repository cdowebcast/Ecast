<xsl:stylesheet xmlns:xsl = "http://www.w3.org/1999/XSL/Transform" version = "1.0" >
 <xsl:output omit-xml-declaration="no" method="text" indent="yes" encoding="UTF-8" />
 <xsl:template match = "/icestats" >

 <xsl:for-each select="source">
 <xsl:value-of select="artist" /><xsl:value-of select="title" />
 </xsl:for-each>

 </xsl:template>
 </xsl:stylesheet> 