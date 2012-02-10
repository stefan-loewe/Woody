function postIt(currentDiv, index)
{
    param="selectFotoIndex=" + index;

    xmlHttp = new XMLHttpRequest();

    xmlHttp.open("POST", "http://127.0.0.1:9009", true);

    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    //xmlHttp.onreadystatechange = handleRequestStateChange;
    xmlHttp.send(param);
}

function init()
{
    for(i = 0; i < document.images.length; i++)
        document.images[i].style.filter = "alpha(opacity=100)";

    deletedFotos = document.location.search.replace("?deleted=", "").split("_");

    for(i = 0; i < deletedFotos.length; i++)
    {
        // only god knows why this does not work
        //document.getElementById("foto" + deletedFotos[i]).style.filter = "alpha(opacity=25)";
        if((img = document.images[deletedFotos[i]]) != null)
            img.style.filter = "alpha(opacity=25)";
    }
}

/*
// function executed when the state of the request changes
function handleRequestStateChange()
{
    // continue if the process is completed
    if (xmlHttp.readyState == 4)
    {
        // continue only if HTTP status is "OK"
        if (xmlHttp.status == 200)
        {
            // retrieve the response
            response = xmlHttp.responseText;

            alert(response);
        }
    }
}
*/