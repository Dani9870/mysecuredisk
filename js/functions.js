var filesToSend=[];
var fileOrder=-1;
var timing=null;
function dropFile(event)
{
    event.preventDefault();
    if (event.dataTransfer.items) {
        for (var i=0;i<event.dataTransfer.items.length;i++)
        {
            var item = event.dataTransfer.items[i];
            if (item.kind =='file')
            {
                const file = item.getAsFile();
                filesToSend.push(file);
            }
        }
        fileOrder=0;
        timing= setTimeout(sendFile(),1);
    }
}
function dragoverFile(event)
{
    event.preventDefault();
}
function sendFile()
{
    if (fileOrder >= filesToSend.length)
    {
      $("#sendingFiles").modal('hide');
      location.reload();
    }
    else
    {
      $("#fileNameToSend").text(filesToSend[fileOrder].name);
      if (fileOrder==0)
      {
         $("#sendingFiles").modal('show');
      }
      var key=getKey($("#email").text());
      const reader = new FileReader()
      reader.onload = (e) => {
        var encryptedFile = encryptFile(e.target.result,key);
        var parentId = $("#currentFolder").val();
        sendFileStream(reader.fileName,encryptedFile,parentId);
      }
      var file = filesToSend[fileOrder];
      reader.fileName=file.name;
      reader.readAsArrayBuffer(file);
    }
}

function createKey() {
    var result = [];
    for (i = 0; i < 32; i++)
      result.push(Math.floor(Math.random() * 255));
    return result;
  }
  
  function getKey(currentEmail){
     currentKey = localStorage.getItem('secureStorage-'+currentEmail);
     if (currentKey == null)
     {
        localStorage.setItem ('secureStorage-'+currentEmail, createKey());
        currentKey = localStorage.getItem('secureStorage-'+currentEmail);
     }
     return stringToByteArray(currentKey);
  }

  function loadKeyFromFileContent (key)
  {
    const realKey=atob(key);
    const customEmail = $("#email").text();
    localStorage.setItem('secureStorage-'+customEmail, realKey);
  }

  function encryptFile(fileContent, key) {
    var aesCtr = new aesjs.ModeOfOperation.ctr(key, new aesjs.Counter(5));
    var toEncrypt = toByteArray(fileContent);
    return btoa(aesCtr.encrypt(toEncrypt));
  }
  function decryptFile(encryptedContent,key) {
    var aesCtr = new aesjs.ModeOfOperation.ctr(key, new aesjs.Counter(5));
    return aesCtr.decrypt(stringToByteArray(atob(encryptedContent)));
  }

  function toByteArray(arrayBuffer) {
    var result = [];
    var array = new Uint8Array(arrayBuffer);
    for (i = 0; i < arrayBuffer.byteLength; i++)
      result.push(array[i]);
    return result;
  }
  function stringToByteArray (str)
  {
    var result = [];
    var data = str.split(',');
    for (i =0; i < data.length; i++)
       result.push(parseInt(data[i]));
    return result;
  }


  function saveAsFile(name, byteArray) {
    var blob = new Blob([byteArray], {
      type: "application/octect-stream"
    });
    var link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = name;
    link.click();
  }
  function sendFileStream(_fileName,_fileContent,_parentId)
  {
      
      var toSend = {
        parentId: _parentId,
        fileName: _fileName,
        fileContent: _fileContent
      };
      $.ajax({
          url: 'controllers/api.php',
          type: 'post',
          data: JSON.stringify(toSend),
          contentType: "application/json",
          success: (data)=>{
             fileOrder++;
             sendFile();
          }
      });
  }
  function getFileStream (_fileName,_folder)
  {
     $.ajax({
       url: 'controllers/api.php?fileName='+_fileName+"&folder="+_folder,
       success: (data) => {
         var response = JSON.parse(data);
         if (response.returnCode == 0 )
         {
            var key=getKey($("#email").text());
            var decryptedFile = decryptFile (response.contentFile,key);
            saveAsFile(response.fileName, decryptedFile);
         }
         else
           alert ("Error downloading file");
       }
     })
  }
  function newFolder()
  {
    var _folderName= $("#newFolderName").val();
    var _parentId = $("#currentFolder").val();
    var toSend = {
      parentId: _parentId,
      folderName: _folderName
    };

    $.ajax({
      url: 'controllers/newfolder.php',
      type: 'post',
      data: JSON.stringify(toSend),
      contentType: "application/json",
      success: (data)=>{
        location.reload();        
      }
  });

  }
  function deleteFolder (_folderName)
  {
    $("#nameRemove").val("")
     $("#messagetitle").text("Eliminar la carpeta "+_folderName);
     $("#message").text("Para eliminar esta carpeta debe escribir \""+_folderName+"\" en el siguiente cuadro de texto");
     $("#messageid").val(_folderName);
     $("#removemodal").modal("show");
     $("#deletebutton").on("click" , ()=>{doDeleteFolder();});
  }
  function doDeleteFolder ()
  {
    if ($("#nameRemove").val()==$("#messageid").val())
    {
      var _parentId = $("#currentFolder").val();
      var toSend = {
        parentId: _parentId,
        folderName: $("#messageid").val()
      };
      $.ajax({
        url: 'controllers/deleteFolder.php',
        type: 'post',
        data: JSON.stringify(toSend),
        contentType: "application/json",
        success: (data)=>{
          location.reload();        
        }
    });
   }
  }
  function deleteFile (_fileName)
  {
     $("#nameRemove").val("")
     $("#messagetitle").text("Eliminar el archivo "+_fileName);
     $("#message").text("Para eliminar este archivo debe escribir \""+_fileName+"\" en el siguiente cuadro de texto");
     $("#messageid").val(_fileName);
     $("#removemodal").modal("show");
     $("#deletebutton").on("click" , ()=>{doDeleteFile();})
  }
  function doDeleteFile ()
  {
    if ($("#nameRemove").val()==$("#messageid").val())
    {
      var _parentId = $("#currentFolder").val();
      var toSend = {
        parentId: _parentId,
        fileName: $("#messageid").val()
      };
      $.ajax({
        url: 'controllers/deleteFile.php',
        type: 'post',
        data: JSON.stringify(toSend),
        contentType: "application/json",
        success: (data)=>{
          location.reload();        
        }
    });
  } 
 }

 function modifyNameFolder (_nameFolder)
 {
    $("#modifyFolderName").val("");
    $("#oldnamefolder").text(_nameFolder);
    $("#modifymodal").modal("show");
 }
 function onModifyNameFolder ()
 {
   if ($("#modifyFolderName").val()!="")
   {
     var _parentId = $("#currentFolder").val();
     var toSend = {
       parentId: _parentId,
       newFolderName: $("#modifyFolderName").val(),
       folderName:  $("#oldnamefolder").text()
     };
     $.ajax({
       url: 'controllers/changeNameFolder.php',
       type: 'post',
       data: JSON.stringify(toSend),
       contentType: "application/json",
       success: (data)=>{
         location.reload();        
       }
   });
 } 
}

function saveKey ()
{
  const key=getKey($("#email").text());
  const content= btoa(key);
  saveAsFile($("#email").text()+"-mysecuredisk.txt",content);
}

const strongRegex = /(?=[A-Za-z0-9@#$%^&+!=.]+$)^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%^&+!=.])(?=.{8,}).*$/;

function savePassword() {
    const _password = $("#password").val();
    const _password2 = $("#password2").val();
    const _email = $("#email").val();
    const _vcode = $("#vcode").val();
    if (_password == _password2 && strongRegex.test(_password)) {
        var toSend = {
            password: _password,
            email: _email,
            vcode: _vcode,
        };
        $.ajax({
            url: 'controllers/changepassword.php',
            type: 'post',
            data: JSON.stringify(toSend),
            contentType: "application/json",
            success: (data) => {
                if (data == "1") {
                    $("#title").text("Contraseña modificada perfectamente");
                    $("#message").text("Pulse en aceptar para it a la página de inicio");
                }
                else {
                    $("#title").text("Error al cambiar la contraseña");
                    $("#message").text("Pulse en aceptar para ir a la página de inicio");
                }
                $("#messageBox").modal("show");
            }
        });

    }
    else {
        if ( _password == _password2)
            $("#notmatch").html("La contraseña debe tener una minúscula, una mayúscula, un número, un caracter especial y ser de longitud mayor a 8");
        else
            $("#notmatch").text("Las contraseñas no coniciden");
        $("#notmatch").removeClass("hidden");
    }

}
function savePasswordInside() {
  const _password = $("#password").val();
  const _password2 = $("#password2").val();
  const _oldpassword = $("#oldpassword").val();
  if (_password == _password2 && strongRegex.test(_password)) {
      var toSend = {
          password: _password,
          oldpassword: _oldpassword
      };
      $.ajax({
          url: 'controllers/changepasswordinside.php',
          type: 'post',
          data: JSON.stringify(toSend),
          contentType: "application/json",
          success: (data) => {
              if (data == "1") {
                  $("#title").text("Contraseña modificada perfectamente");
                  $("#message").text("Pulse en aceptar para ir a sus archivos");
              }
              else {
                  $("#title").text("Error al cambiar la contraseña");
                  $("#message").text("Pulse en aceptar para volver a sus archivos");
              }
              $("#messageBox").modal("show");
          }
      });

  }
  else {
    if ( _password == _password2)
        $("#notmatch").html("La contraseña debe tener una minúscula, una mayúscula, un número, un caracter especial y ser de longitud mayor a 8");
    else
          $("#notmatch").text("Las contraseñas no coniciden");
      $("#notmatch").removeClass("hidden");
  }

}
function registerUser () {
  const _password = $("#password").val();
  if ( strongRegex.test(_password))
     return true;
  else
  {
    
    $("#notmatch").html("La contraseña debe tener una minúscula, una mayúscula, un número, un caracter especial y ser de longitud mayor a 8");
    $("#notmatch").removeClass("hidden");
    return false;
  }

}


