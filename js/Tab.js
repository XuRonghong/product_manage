/**
 * ��ǩ������ƶ��¼��Ĵ�������
 * @return
 */


document.getElementById("tabbar-div").onmouseover = function(e)
{
  var obj = Utils.srcElement(e);

  if (obj.className == "tab-back")
  {
    obj.className = "tab-hover";
  }
}

document.getElementById("tabbar-div").onmouseout = function(e)
{
  var obj = Utils.srcElement(e);

  if (obj.className == "tab-hover")
  {
    obj.className = "tab-back";
  }
}

/**
 * ���������ǩ���¼��ĺ���
 * @param : e  FireFox �¼����
 *
 * @return
 */
document.getElementById("tabbar-div").onclick = function(e)
{ 
  var obj = Utils.srcElement(e);

 if (obj.className == "tab-front" || obj.className == '' || obj.tagName.toLowerCase() != 'span')
  {
    return;
  }
  else
  {
    /* ��ʼ��ϵͳ���� */
    if (obj.id == 'attribute-tab' && executed === false)
    {
      initGetAttribute(document.getElementById('category_key').value);
      executed = true;
    }
    objTable = obj.id.substring(0, obj.id.lastIndexOf("-")) + "-table";

    var tables = document.getElementsByTagName("table");
    var spans  = document.getElementsByTagName("span");

    for (i = 0; i < tables.length; i ++ )
    {
      if (tables[i].id == objTable)
      {
        tables[i].style.display = (Browser.isIE) ? "block" : "table";
      }
      else
      {
        var tblId = tables[i].id.match(/-table$/);

        if (tblId == "-table")
        {
          tables[i].style.display = "none";
        }
      }
    }

    for (i = 0; spans.length; i ++ )
    {
      if (spans[i].className == "tab-front")
      {
        spans[i].className = "tab-back";
        obj.className = "tab-front";
        break;
      }
    }
  }
}

