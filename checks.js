function isEmailAddr(elem)
{
    var str = elem.value;
    str = str.toLowerCase();
    if (str.indexOf("@") > 1)
    {
	var addr = str.substring(0,str.indexOf("@"));
	var domain = str.substring(str.indexOf("@") + 1,str.length);
	if (domain.indexOf(".") == -1)
        {
            alert("Verify the domain portion of the email address.");
            return false;
        }
        for ( var i=0; i<addr.length; i++ )
        {
            oneChar = addr.charAt(i).charCodeAt(0);
            if ((i==0 && (oneChar==45 || oneChar==46)) ||
                (i==addr.length-1 && oneChar==46))
            {
                alert("Verify the user name portion of the email address.");
                return false;
            }
            if ( oneChar==45 || oneChar==46 || oneChar==95 ||
                 (oneChar>47 && oneChar<58) || (oneChar>96 && oneChar<123))
            {
                continue;
            }
            else
            {
                alert("Verify the user name portion of the email address.");
                return false;
            }
	}
        for ( i=0; i<domain.length; i++ )
        {
            oneChar = domain.charAt(i).charCodeAt(0);
            if ((i==0 && (oneChar==45 || oneChar==46)) ||
                ((i==domain.length-1 || i==domain.length-2) && oneChar==46))
            {
                alert("Verify the domain portion of the email address.");
                return false;
            }
            if (oneChar==45 || oneChar==46 || oneChar==95 ||
                (oneChar>47 && oneChar<58) || (oneChar>96 && oneChar<123))
            {
                continue;
            }
            else
            {
                alert("Verify the domain portion of the email address.");
                return false;
            }
        }
        return true;
    }
    alert("The email address may not be formatted correctly. Please verify.");
    return false;
}
function isNotEmpty(elem) 
{
    var str = elem.value;
    var re = /.+/;
    if (!str.match(re))
    {
	alert("Please fill in the required field.");
	return false; 
    } 
    else 
    { 
	return true; 
    } 
}
function validateForm( form )
{
    if (isNotEmpty(form.call))
    {
	if (isNotEmpty(form.email))
        {
	    if (isEmailAddr(form.email)) 
	    {
		return true;
	    }
	}
    }
    return false;
}

