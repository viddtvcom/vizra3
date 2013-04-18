<?php

$tempvars['general']['{$vurl}'] = 'Müşteri Paneli Adresi';
//$tempvars['general']['{$signature}'] = 'İmza';

$tempvars['client']['{$Client_clientID}'] = 'Müşteri No';
$tempvars['client']['{$Client_name}'] = 'Ad Soyad';
$tempvars['client']['{$Client_email}'] = 'Email';
$tempvars['client']['{$Client_company}'] = 'Şirket Adı';
$tempvars['client']['{$Client_address}'] = 'Adres';
$tempvars['client']['{$Client_state}'] = 'Semt';
$tempvars['client']['{$Client_zip}'] = 'Posta Kodu';
$tempvars['client']['{$Client_city}'] = 'Şehir';
$tempvars['client']['{$Client_country}'] = 'Ülke';
$tempvars['client']['{$Client_phone}'] = 'Telefon';
$tempvars['client']['{$Client_cell}'] = 'Cep Telefonu';
$tempvars['client']['{$Client_dateAdded}'] = 'Üyelik Tarihi';
$tempvars['client']['{$Client_dateLogin}'] = 'Son Login Tarihi';
$tempvars['client']['{$Client_ipLogin}'] = 'Son Login Ip';

if ($Tpl->type != 'support' && $Tpl->type != 'user' && $Tpl->type != 'custom' && $Tpl->templateID != 3 && $_cpage != 'mass_mail') {
    $tempvars['order']['{$Order_orderID}'] = 'Sipariş No';
    $tempvars['order']['{$Order_status}'] = 'Sipariş Durumu';
    $tempvars['order']['{$Order_price}'] = 'Sipariş Tutarı';
    $tempvars['order']['{$Order_paycurID}'] = 'Sipariş Kuru';
    $tempvars['order']['{$Order_period}'] = 'Sipariş Periyodu';
    $tempvars['order']['{$Order_title}'] = 'Paket Adı';
    $tempvars['order']['{$Order_dateAdded}'] = 'Sipariş Tarihi';
    $tempvars['order']['{$Order_dateStart}'] = 'Başlama Tarihi';
    $tempvars['order']['{$Order_dateEnd}'] = 'Bitiş Tarihi';
    $tempvars['order']['{$Order_username}'] = 'Kullanıcı Adı';
    $tempvars['order']['{$Order_password}'] = 'Şifre';
    $tempvars['order']['{$Order_domain}'] = 'Hesabın Açıldığı Domain';
    $tempvars['order']['{$Order_server_name}'] = 'Sunucu Adı';
    $tempvars['order']['{$Order_server_ip}'] = 'Sunucu Ip';
    $tempvars['order']['{$Order_server_hostname}'] = 'Sunucu Hostname';
    $tempvars['order']['{$Order_XXXX_YYYY}'] = 'X Modülü Y Özelliği, Örnek: {$Order_cpanel_bwlimit} : Cpanel Modülü, Trafik Limiti';
    $tempvars['order']['{$Service_service_name}'] = 'Servis Adı';
}

if ($Tpl->templateID == 3) {
    $tempvars['payment']['{$Payment_paymentID}'] = 'Ödeme No';
    $tempvars['payment']['{$Payment_paymentStatus}'] = 'Ödeme Durumu';
    $tempvars['payment']['{$Payment_datePayed}'] = 'Onaylandığı Tarih';
    $tempvars['payment']['{$Payment_amount}'] = 'Ödeme Miktarı';
    $tempvars['payment']['{$Payment_paycurID}'] = 'Ödeme Kuru';
    $tempvars['payment']['{$Payment_dateAdded}'] = 'Ödeme Kayıt Tarihi';
    $tempvars['payment']['{$Payment_moduleID}'] = 'Ödeme Modülü';
}

if ($Tpl->templateID == 12 || $Tpl->templateID == 13) {
    $tempvars['orderbill']['{$OrderBill_billID}'] = 'Borç No';
    $tempvars['orderbill']['{$OrderBill_status}'] = 'Borç Durumu';
    $tempvars['orderbill']['{$OrderBill_amount}'] = 'Borç Miktarı';
    $tempvars['orderbill']['{$OrderBill_paycurID}'] = 'Borç Kuru';
    $tempvars['orderbill']['{$OrderBill_dateDue}'] = 'Son Ödeme Tarihi';
    $tempvars['orderbill']['{$OrderBill_datePayed}'] = 'Ödendiği Tarih';
    $tempvars['orderbill']['{$OrderBill_dateStart}'] = 'Dönem Başlangıcı';
    $tempvars['orderbill']['{$OrderBill_dateEnd}'] = 'Dönem Bitişi';
}

if ($Tpl->type == 'domain') {
    $tempvars['domain']['{$Domain_domain}'] = 'Alan Adı';
    $tempvars['domain']['{$Domain_status}'] = 'Alan Adı Durumu';
    $tempvars['domain']['{$Domain_dateReg}'] = 'Tescil Tarihi';
    $tempvars['domain']['{$Domain_dateExp}'] = 'Tescil Bitiş Tarihi';
    $tempvars['domain']['{$Domain_orderID}'] = 'Sipariş No';
}

if ($Tpl->type == 'support') {
    $tempvars['ticket']['{$Ticket_ticketID}'] = 'Bilet No';
    $tempvars['ticket']['{$Ticket_subject}'] = 'Bilet Konusu';
    $tempvars['ticket']['{$Ticket_status}'] = 'Bilet Durumu';
    $tempvars['ticket']['{$Ticket_dateAdded}'] = 'Kayıt Tarihi';
    $tempvars['ticket']['{$Ticket_dateUpdated}'] = 'Güncelleme Tarihi';
}






