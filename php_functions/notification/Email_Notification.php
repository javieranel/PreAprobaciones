<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "../PHPMailer/Exception.php";
require_once "../PHPMailer/PHPMailer.php";
require_once "../PHPMailer/SMTP.php";



/**
 * Función principal para enviar correos
 */
function enviarCorreo($asunto, $titulo, $categoria, $nombre, $empresa)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();

        // CONFIGURA AQUÍ TU SMTP REAL
        $mail->Host       = 'smtp.office365.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'notificacion@melonesoilterminal.com';
        $mail->Password   = 'n@x1LTm#';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom(
            'notificacion@melonesoilterminal.com',
            'Sistema de Documentos'
        );

        // Destinatario
        $mail->addAddress('javieranel0107@gmail.com');
        $mail->AddAddress('jtapia@melonesterminal.com');
        //$mail->AddAddress('knavarro@melonesterminal.com');

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->Subject = $asunto;

        $logo = realpath('../../../assets/image/logo_nombre.png');

            if ($logo && file_exists($logo)) {
                $mail->AddEmbeddedImage(
                    $logo,
                    'logo_empresa',
                );
            }




        $mail->Body = ' 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        

<div style="
    font-family: Arial, Helvetica, sans-serif;
    background:#f4f6f9;
    padding:30px;
">

    <table width="700" align="center" cellpadding="0" cellspacing="0"
        style="
            background:#ffffff;
            border-radius:10px;
            overflow:hidden;
            border:1px solid #dcdcdc;
        ">

        <!-- ENCABEZADO -->
        <tr>
            <td style="
                background:#003366;
                padding:30px;
                text-align:center;
                border-bottom:5px solid #f0ad4e;
            ">

                <h1 style="
                    color:#ffffff;
                    margin:0;
                    font-size:26px;
                    font-weight:bold;
                ">
                    Melones Oil Terminal Inc.
                </h1>

                <p style="
                    color:#ffffff;
                    margin-top:8px;
                    font-size:16px;
                ">
                    Notificación Automática del Sistema de Gestion de Pre-Aprobaciones
                </p>

            </td>
        </tr>

        <!-- CONTENIDO -->
        <tr>
            <td style="padding:30px;">

                <h2 style="
                    color:#003366;
                    margin-top:0;
                    border-bottom:2px solid #003366;
                    padding-bottom:10px;
                ">
                    '.$titulo.'
                </h2>

                <p style="
                    color:#555;
                    font-size:14px;
                    line-height:22px;
                ">
                    Se ha generado una nueva actividad dentro del Sistema de Gestion de Pre-Aprobaciones.
                </p>

                <table width="100%"
                       cellpadding="10"
                       cellspacing="0"
                       style="
                            border-collapse:collapse;
                            margin-top:20px;
                            border:1px solid #ddd;
                       ">

                    <tr style="background:#f0f4f8;">
                        <td width="35%">
                            <strong>Categoría</strong>
                        </td>
                        <td>'.$categoria.'</td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Nombre</strong>
                        </td>
                        <td>'.$nombre.'</td>
                    </tr>

                    <tr style="background:#f0f4f8;">
                        <td>
                            <strong>Empresa</strong>
                        </td>
                        <td>'.$empresa.'</td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Fecha</strong>
                        </td>
                        <td>'.date('d/m/Y H:i:s').'</td>
                    </tr>

                </table>

                <br>

                <div style="
                    background:#eaf4ff;
                    border-left:5px solid #003366;
                    padding:15px;
                    color:#444;
                    font-size:14px;
                ">
                    Este registro ha sido procesado automáticamente por el Sistema de Gestion de Pre-Aprobaciones.
                </div>

            </td>
        </tr>

        <!-- PIE -->
        <tr>
            <td style="
                background:#f8f9fa;
                text-align:center;
                padding:20px;
                font-size:12px;
                color:#666;
                border-top:1px solid #ddd;
            ">

                <strong>Melones Oil Terminal Inc.</strong>

                <br><br>

                Sistema de Gestion de Pre-Aprobaciones

                <br><br>

                © '.date('Y').' Todos los derechos reservados.

            </td>
        </tr>

    </table>

</div>

';

        return $mail->send();
    } catch (Exception $e) {

        error_log("Error PHPMailer: " . $mail->ErrorInfo);

        return false;
    }
}

/**
 * NUEVO REGISTRO
 */
function enviarCorreoNuevoRegistro($con, $idDocumento)
{
    $sql = "SELECT * FROM documentos WHERE id = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $idDocumento);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($doc = $resultado->fetch_assoc()) {

        return enviarCorreo(
            "Nuevo Registro",
            "Se ha agregado un nuevo registro",
            $doc['categoria'],
            $doc['nombre'],
            $doc['nombre_empresa']
        );
    }

    return false;
}

/**
 * REGISTRO EDITADO POR ID
 */
function enviarCorreoEdicionPorId($con, $idDocumento)
{
    $sql = "SELECT * FROM documentos WHERE id = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $idDocumento);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($doc = $resultado->fetch_assoc()) {

        return enviarCorreo(
            "Registro Editado",
            "Se ha modificado un registro",
            $doc['categoria'],
            $doc['nombre'],
            $doc['nombre_empresa']
        );
    }

    return false;
}

/**
 * REGISTRO EDITADO
 */
function enviarCorreoEdicion($categoria, $nombre, $empresa)
{
    return enviarCorreo(
        "Registro Editado",
        "Se ha modificado un registro",
        $categoria,
        $nombre,
        $empresa
    );
}

/**
 * REGISTRO ELIMINADO
 */
function enviarCorreoEliminacion($nombre, $categoria, $empresa)
{
    return enviarCorreo(
        "Registro Eliminado",
        "Se ha eliminado un registro",
        $categoria,
        $nombre,
        $empresa
    );
}
