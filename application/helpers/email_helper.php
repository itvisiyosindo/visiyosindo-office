<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

function emailConfig(){
    $config = array();
        $config['charset']      = 'utf-8';
        $config['useragent']    = 'Codeigniter';
        $config['protocol']     = "smtp";
        $config['mailtype']     = "html";
        $config['smtp_host']    = "ssl://smtp.gmail.com";
        $config['smtp_port']    = "465";
        $config['smtp_timeout'] = "465";
        $config['smtp_user']    = "it.visiyosindo@gmail.com";
        $config['smtp_pass']    = "Visiyosindo@passbaru2023";
        $config['crlf']         = "\r\n";
        $config['newline']      = "\r\n";
        $config['wordwrap']     = TRUE;
        return $config;
    }

function emailVerifikasi($data){
        $dataMail['subject'] = '[Verifikasi Akun] Pendaftaran Akun Kepegawaian Visiyosindo a/n '.$data['nama'];
        $dataMail['mail_to'] = $data['email'];
        $dataMail['body']    = '
                                Dear '.$data['nama'].',<br>
                                
                                Silahkan klik link berikut untuk melakukan verifikasi pendaftaran:<br><br>
                                Link Verifikasi <a href="'.base_url($data['link_verif']).'" target="_blank">Klik disini</a><br><br>
                                
                                Terima Kasih<br><br>

                                <i>
                                Do not reply to this computer-generated email</i>';
        sendMail($dataMail);
        return true; 
}

function emailVerifikasiResetPass($data)
{
    $dataMail['subject'] = '[Verifikasi Reset Password] Reset Password LAY BK a/n '.$data['nama'];
    $dataMail['mail_to'] = $data['email'];
    $dataMail['body']    = '
                            Dear '.$data['nama'].',<br>
                            
                            Silahkan klik link berikut untuk melakukan reset password,
                            link akan mati dalam 30 menit.<br><br>
                            Link Reset Password <a href="'.base_url($data['link_verif']).'" target="_blank">Klik disini</a><br><br>
                            
                            Terima Kasih<br><br>

                            <i>Contact us at pmb@pcr.ac.id. Do not reply to this computer-generated email.<br>
                            Politeknik Caltex Riau, Jl. Umban Sari (Patin) No. 1 Rumbai Pekanbaru - Riau 28265</i>';
    
    sendMail($dataMail);
    return true; 
}

function emailResetPassword($data)
{
    $dataMail['subject'] = '[Password Baru] Reset Password LAY BK a/n '.$data['nama'];
    $dataMail['mail_to'] = $data['email'];
    $dataMail['body']    = '
                                Dear '.$data['nama'].',<br>
                                
                                Berikut adalah password baru anda.<br><br>
                                Password: '.$data['new_pass'].'<br><br>
                                
                                Jangan beritahu password anda pada siapapun.<br><br>

                                <i>Contact us at pmb@pcr.ac.id. Do not reply to this computer-generated email.<br>
                                Politeknik Caltex Riau, Jl. Umban Sari (Patin) No. 1 Rumbai Pekanbaru - Riau 28265</i>';
        
        sendMail($dataMail);
        return true; 
}

function sendMail($dataMail,$attachment=FALSE){
    $CI = get_instance();
    $CI->load->library('email');
    $CI->email->initialize(emailConfig());
    $CI->email->clear(TRUE);
    $CI->email->from('noreply@0710', 'Databank Visiyosindo');

    if($dataMail['mail_to'])
        $CI->email->to($dataMail['mail_to']);

    $CI->email->subject($dataMail['subject']);
    $CI->email->message($dataMail['body']);

    if($attachment){
        $CI->email->attach($attachment);
    }
    
    if (!$CI->email->send()){
        return FALSE;
    }
    return TRUE;
}


?>