selects = {
  add: function(strid, strero, strers)
  {
    var tobj = strid;
    if (tobj)
    {
      var ti, tstr, tisext;
      for (ti = 0; ti < tobj.options.length; ti ++)
      {
       if (tobj.options[ti].text == strero &&  tobj.options[ti].value == strers)
       {tisext = true;}
      }
      if (!tisext){tobj.options.add(new Option(strero, strers));}
    }
  },
  remove: function(strid)
  {
    var tobj = strid;
    if (tobj)
    {
      var tidx = tobj.selectedIndex;
      if (!(tidx == -1)){tobj.options[tidx] = null;}
    }
  },
  displace: function(strid, strindex, strkey)
  {
    if (strindex >= 0)
    {
      var tobj = strid;
      if (tobj)
      {
        var tstrvalue, tstrtext;
        tstrtext = tobj.options[strindex].text;
        tstrvalue = tobj.options[strindex].value;
        if (strkey == 38)
        {
          if (!(strindex == 0))
          {
            tobj.options[strindex].text = tobj.options[strindex - 1].text;
            tobj.options[strindex].value = tobj.options[strindex - 1].value;
            tobj.options[strindex - 1].text = tstrtext;
            tobj.options[strindex - 1].value = tstrvalue;
          }
        }
        if (strkey == 40)
        {
          if (!(strindex == (tobj.options.length - 1)))
          {
            tobj.options[strindex].text = tobj.options[strindex + 1].text;
            tobj.options[strindex].value = tobj.options[strindex + 1].value;
            tobj.options[strindex + 1].text = tstrtext;
            tobj.options[strindex + 1].value = tstrvalue;
          }
        }
      }
    }
  }
}