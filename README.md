Woody
=====

Woody - Winbinder's Object-Oriented Dialog Ynterface

What is Woody?
==================

Woody is a object-oriented layer to be used on top of [WinBinder - the native Windows binding for PHP](https://github.com/stefan-loewe/WinBinder/ "WinBinder - the native Windows binding for PHP").

What is needed to start using Woody?
====================================

You will need PHP 5.4 plus the respective winbinder extension. Visit [the WinBinder github site](https://github.com/stefan-loewe/WinBinder/ "the WinBinder guthub site") for getting the latest version of the extension.

How to install Woody?
====================================

Woody can be installed manually, or via composer. Woody has a dependency to a Utils package that defines a few common classes - this dependency is taken care of when installing via composer.

Of course, you need to install the winbinder extension manually.

How to write an application with Woody?
======================================

Have a look in [./doc/examples](https://github.com/stefan-loewe/woody/tree/master/doc/examples "examples") and check out the [test suite](https://github.com/stefan-loewe/woody/tree/master/test/source/Woody "test suite").

Frequently Asked Questions
==========================

Q: Why uses the HTMLControl the rendering engine of Internet Explorer 7, when I have Internet Explorer 8/9 installed?  
A: add a DWORD with key name "php.exe" and decimal value of "9999" (hexadecimal "270F") in the registry under the path "HKEY\_CURRENT\_USER\Software\Microsoft\Internet Explorer\Main\FeatureControl\FEATURE\_BROWSER\_EMULATION"

- There are also other values possible - for more information, check these links:
  - <http://www.google.de/search?q=IWebBrowser2+ie9&ie=utf-8&oe=utf-8&aq=t&rls=org.mozilla:de:official&client=firefox-a>
  - <http://stackoverflow.com/questions/6914664/iwebbrowser2-object-uses-ie7-version-instead-of-the-ie-version-installed-on-the>
  - <http://msdn.microsoft.com/en-us/library/ee330730%28v=vs.85%29.aspx>
  - <http://stackoverflow.com/questions/1786905/c-sharp-web-browser-component-is-ie7-not-ie8-how-to-change-this>
  - <http://blogs.msdn.com/b/ie/archive/2009/03/10/more-ie8-extensibility-improvements.aspx>
  - <http://www.codeproject.com/KB/COM/cwebpage.aspx>