<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
            <!--<link href="http://services.mwork.com.ar/storage/app/public/css/mail.css" type = "text/css" rel="stylesheet">-->
            <title>
                [SUBJECT]
            </title>
            <style type="text/css">
                @media screen and (max-width: 600px) {
    table[class="container"] {
        width: 95% !important;
    }
}

#outlook a {
    padding: 0;
}

body {
    width: 100% !important;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
    min-width: 1000px;

}

.ExternalClass {
    width: 100%;
}

.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
    line-height: 100%;
}

#backgroundTable {
    margin: 0;
    padding: 0;
    width: 100% !important;
    line-height: 100% !important;
}

img {
    outline: none;
    text-decoration: none;
    -ms-interpolation-mode: bicubic;
}

a img {
    border: none;
}

.image_fix {
    display: block;
}

p {
    margin: 1em 0;
}

h1, h2, h3, h4, h5, h6 {
    color: black !important;
}

h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {
    color: blue !important;
}

h1 a:active, h2 a:active, h3 a:active, h4 a:active, h5 a:active, h6 a:active {
    color: red !important;
}

h1 a:visited, h2 a:visited, h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
    color: purple !important;
}

table td {
    border-collapse: collapse;
}

table {
    border-collapse: collapse;
    mso-table-lspace: 0pt;
    mso-table-rspace: 0pt;
}

a {
    color: white;
    text-decoration: none
}

h2 {
    color: #181818;
    font-family: Helvetica, Arial, sans-serif;
    font-size: 28px;
    line-height: 22px;
    font-weight: normal;
}
p {
    color: #555;
    font-family: Helvetica, Arial, sans-serif;
    font-size: 16px;
    line-height: 160%;
}
.boton{
  width: 170px;
  height: 50px;
  border: none;
  background: #3a7999;
  box-sizing: border-box;
  transition: all 500ms ease;
	color:white;
  font-weight: bold;
  font-size: 14px;

}

.main{
  clear:left;
	position:relative;
	float:left;
  /*width:100%;*/
  text-align: center;

	/*position:relative;*/
  width:600px;
  /*left: 50%;
  margin-left:-400px;*/
}

.imagen img
{
    max-width: 100%;
}
.fin{
	clear:left;
	position:relative;
	float:left;
  width:600px;
  /*left: 50%;
  margin-left:-400px;*/

}
.fin2{
	clear:left;
	position:relative;
	float:left;
  width:800px;
  /*left: 50%;
  margin-left:-400px;*/

}
#caja_left{
	clear:left;
	position:relative;
	float:left;
  width:100%;
  text-align: center;
}

            </style>
        </link>
    </head>
    <body>

          <div class="main" >

            <div id="caja_left" >

                    <div class="imagen" >

                      <img alt="Logo" data-default="placeholder" src="http://services.mwork.com.ar/storage/app/public/images/registro_mwork_ok2.png"/>

                    </div>

            </div>
                <div style="margin-top:15px;">

                          <h3>
                              ¡Te damos la bienvenida a MWork!
                          </h3>

                </div>
                <div id="caja_left">
                          <p>
                              Hola {{ $name }}:
                          </p>
                          <p>
                              Tu registro está casi listo, para confirmar tu correo y finalizar tu login solo debes hacer click en el botón siguiente:
                          </p>

                </div>
                <div id="caja_left" >
                          <a class="link2" href="http://www.mwork.com.ar/verificar-correo/{{ $code }}" target="_blank">
                          <button class="boton">
                                Confirmar

                            <!--<span style="font-size:16px">Confirmar</span>-->
                          </button>
                          </a>
                </div>
                <div id="caja_left" >

                          <p>
                              Puedes revisar y modificar tus datos de perfil cuando lo desees.
                          </p>

                </div>

          </div>


          <div class="fin" >

                <div class="imagen">
                    <img alt="Logo" data-default="placeholder" src="http://services.mwork.com.ar/storage/app/public/images/registro_mwork_pie.png"/>
                </div>

          </div>
    </body>
</html>
