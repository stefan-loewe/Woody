var selection = new Array();

var previousIndex = null;

function setSelected(index, selected)
{
    selection[index] = selected;

    document.getElementById('btnFoto' + index).style.backgroundColor = selection[index] ? "#316AC5" : "#ECE9D8";
}

function getSelectionRange(index)
{
    range = new Array();

    if(event.shiftKey && previousIndex != null)
    {
        for(var i = Math.min(previousIndex, index); i <= Math.max(previousIndex, index); i++)
            range.push(i);
    }

    else
        range.push(index);

    return range;
}

function setSelection(range)
{
    if(event.shiftKey)
    {
        for(var i = 0; i < range.length; i++)
            setSelected(range[i], true);
    }
    else
        setSelected(range[0], (selection[range[0]] == undefined || selection[range[0]] == false));
}

function update(range)
{
    rng = range[0];
    
    if(range.length > 1)
        rng = rng + '_' + range[range.length - 1];

    param="selectedFotos=" + rng;

    xmlHttp = new XMLHttpRequest();

    xmlHttp.open("POST", "http://127.0.0.1:9009", true);

    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xmlHttp.send(param);
}

function postIt(currentDiv, index)
{
    selectionRange = getSelectionRange(index);

    setSelection(selectionRange);

    update(selectionRange);

    previousIndex = index;
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