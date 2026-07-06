<?php

require_once "../connection/db_connection.php";

require_once "./PHPMailer/PHPMailer.php";
require_once "./PHPMailer/SMTP.php";
require_once "./PHPMailer/Exception.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// ===============================
// FECHAS
// ===============================

$fecha_actual = date('Y-m-d');

$fecha_limite = date(
    'Y-m-d',
    strtotime('+60 days')
);


// ===============================
// DOCUMENTOS A REVISAR
// ===============================

$columnas_vencimiento = [

    'permiso_sne',
    'exencion_acp',
    'licencia_amp',
    'patente_de_navegacion',
    'ITC',
    'PYI',
    'CLC',
    'CLBC',
    'COC',
    'safety_equipment',
    'safety_radio',
    'safety_construction',
    'loadline',
    'SMC',
    'DOC',
    'ISPPC',
    'IAPP',
    'IOPP',
    'issc',
    'LOA',
    'LBP',
    'MAX_M',
    'Displacement_MAX',
    'cedula_vencimiento',
    'CIA_AMP',
    'CIA_CNA_1',
    'CIA_CNA_2',
    'CIA_SNE_1',
    'CIA_SNE_2',
    'CIA_POLIZA',
    'REMOL_POLIZA',
    'PILOT_LIC_OPERACION',
    'PILOT_COMPETENCIA'

];



// ===============================
// CONSULTA
// ===============================


$sql = "

SELECT 

id,
nombre,
nombre_empresa,
categoria,

" . implode(",", $columnas_vencimiento) . "

FROM documentos

";


$resultado = $con->query($sql);



// Array donde guardaremos por categoría

$documentos = [];

$cantidad = 0;



while ($row = $resultado->fetch_assoc()) {



    foreach ($columnas_vencimiento as $campo) {



        $fecha = $row[$campo];



        if (
            empty($fecha) ||
            $fecha == "0000-00-00"
        ) {

            continue;
        }



        // próximos a vencer

        if (
            strtotime($fecha) <= strtotime($fecha_limite)
        ) {


            $categoria = $row['categoria'];


            if (!isset($documentos[$categoria])) {

                $documentos[$categoria] = "";
            }



            // ============================
            // ESTADO DEL DOCUMENTO
            // ============================


            if (strtotime($fecha) < strtotime($fecha_actual)) {


                $estado = "

        <span style='
        color:#dc3545;
        font-weight:bold;
        '>

        🔴 Vencido

        </span>

        ";
            } else {


                $dias = floor(
                    (strtotime($fecha) - strtotime($fecha_actual))
                        /
                        (60 * 60 * 24)
                );



                $estado = "

        <span style='
        color:#ffc107;
        font-weight:bold;
        '>

        🟡 Próximo a vencer
        <br>
        ($dias días)

        </span>

        ";
            }





            $documentos[$categoria] .= "

    <tr>


    <td style='padding:10px;'>
    {$row['nombre']}
    </td>


    <td style='padding:10px;'>
    {$row['nombre_empresa']}
    </td>



    <td style='padding:10px;'>
    $campo
    </td>



    <td style='padding:10px;'>
    " . date('d/m/Y', strtotime($fecha)) . "
    </td>



    <td style='padding:10px;text-align:center;'>
    $estado
    </td>



    </tr>


    ";



            $cantidad++;
        }
    }
}



// ===============================
// SI NO HAY DOCUMENTOS
// ===============================


if ($cantidad == 0) {

    echo "No hay documentos próximos a vencer";

    exit;
}



// ===============================
// ARMAR TABLAS POR CATEGORIA
// ===============================


$lista = "";



foreach ($documentos as $categoria => $datos) {



    $lista .= "


<h3 style='
color:#dc3545;
margin-top:30px;
border-bottom:2px solid #dc3545;
padding-bottom:8px;
'>

📂 $categoria

</h3>



<table width='100%' 
style='
border-collapse:collapse;
font-family:Arial;
font-size:14px;
'>


<tr style='background:#343a40;color:white;'>


<th style='padding:10px;'>
Nombre
</th>


<th style='padding:10px;'>
Empresa
</th>


<th style='padding:10px;'>
Documento
</th>


<th style='padding:10px;'>
Vencimiento
</th>

<th style='padding:10px;'>
Estado
</th>


</tr>



$datos



</table>



";
}





// ===============================
// ENVIO DE CORREO
// ===============================


$mail = new PHPMailer(true);



try {


    $mail->isSMTP();


    $mail->Host = "smtp.office365.com";


    $mail->SMTPAuth = true;


    // CAMBIAR DATOS

    $mail->Username = "notificacion@melonesoilterminal.com";

    $mail->Password = "n@x1LTm#";



    $mail->SMTPSecure = "tls";

    $mail->Port = 587;



    $mail->setFrom(
        'notificacion@melonesoilterminal.com',
        'Sistema de Documentos'
    );



    // Destinatario
    $mail->addAddress('javieranel0107@gmail.com');
    $mail->AddAddress('jtapia@melonesterminal.com');
    $mail->AddAddress('knavarro@melonesterminal.com');

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';



    $mail->Subject =
        "⚠️ Alerta Documentos Próximos a Vencer";



    $mail->Body = "



<div style='
font-family:Arial;
background:#f4f6f9;
padding:20px;
'>


<div style='
background:white;
padding:25px;
border-radius:8px;
'>


<h2 style='color:#dc3545;'>

⚠️ Alerta de Documentos Próximos a Vencer

</h2>



<p>

El sistema de control documental detectó documentos próximos a vencer.

</p>



<p>

Fecha de revisión:

<b>
" . date('d/m/Y') . "
</b>

</p>



<p>

Total documentos encontrados:

<b>
$cantidad
</b>

</p>



$lista



<br>


<hr>


<p style='color:#777;font-size:12px;'>

Correo generado automáticamente.
Sistema de Control Documental.

</p>



</div>


</div>



";



    $mail->send();



    echo "Correo enviado correctamente";
} catch (Exception $e) {


    echo "Error: " . $mail->ErrorInfo;
}
