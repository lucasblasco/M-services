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
    color: #000;
}

h2 {
    color: #181818;
    font-family: Helvetica, Arial, sans-serif;
    font-size: 24px;
    line-height: 22px;
    font-weight: normal;
}

p {
    color: #555;
    font-family: Helvetica, Arial, sans-serif;
    font-size: 16px;
    line-height: 160%;
}
li {
    color: #555;
    font-family: Helvetica, Arial, sans-serif;
    font-size: 16px;
    line-height: 160%;
    text-align: justify;
}

.boton{
  width: 100px;
  height: 50px;
  border: none;
  background: #3a7999;
  box-sizing: border-box;
  transition: all 500ms ease;
	color:white;
  font-weight: bold;
  font-size: 14px;

}

.boton:hover{
	cursor:pointer;
  background: rgba(0,0,0,0);
  color: #3a7999;
  box-shadow: inset 0 0 0 3px #3a7999;
}
.main{
  clear:left;
	position:relative;
	float:left;
  /*width:100%;*/
  text-align: center;

	/*position:relative;*/
  width:600px;
}

.imagen img
{
    max-width: 100%;
}
.fin2{
	clear:left;
	position:relative;
	float:left;
  width:400px;
  left: 50%;
  margin-left:-200px;

}
.fin{
	clear:left;
	position:relative;
	float:left;
  width:600px;

}
#caja_left{
	clear:left;
	position:relative;
	float:left;
  width:100%;
  text-align: justify;
}


            </style>
    </head>
    <body>




          <div class="main" >
            <div class="fin2" >

                  <div class="imagen">
                    <img alt="Logo" data-default="placeholder" src="http://services.mwork.com.ar/storage/app/public/images/logo_movilidad.jpg"/>
                  </div>

            </div>
              <div id="caja_left">
                <p>
                    Hola {{ $name }}!,

                </p>

              </div>
                <div  >

                  <h3>
                    ¡Gracias por participar de {{ $event }}!

                  </h3>

                </div>
                <div id="caja_left">
                  <p >
                    Confirmamos tu inscripción, ten en cuenta que:
                  <ul style="text-align:justify">
                    <li>No es necesario imprimir éste mail como comprobante de entrada.</li>
                    <li>En la agenda encontrarás las actualizaciones del evento que se incorporen.</li>
                    <li>Puedes inscribirte en las actividades que desees.</li>
                    <li>Accediendo a tu perfil tendrás opción a revisar y modificar tus datos.</li>
                    <li>Cualquier duda tienes a disposición contacto@mwork.com.ar para comunicarte.</li>
                  </ul>
                  </p>


                </div>

                <div id="caja_left" >
                      <p>
                        Puedes seguir las novedades en nuestras redes sociales
                      </p>
                </div>

                <div id="caja_left" >
                       <!--   <h2>
                              {2{ $event }} -FALTARIA DIA / LUGAR / CALLE N° / CIUDAD / PAIS
                          </h2>---->
                </div>
                <div id="caja_left" >
                  <h2>
                      Te esperamos!
                  </h2>
                </div>

          </div>


          <div class="fin" >

                <div class="imagen">
                    <img alt="Logo" data-default="placeholder" src="http://services.mwork.com.ar/storage/app/public/images/organizan.png"/>
                </div>

          </div>


    </body>
</html>
