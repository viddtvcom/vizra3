<?php


$_compinfo['name'] = Setting::type('textbox')->lab('Firma İsmi')->width('200');
$_compinfo['email'] = Setting::type('textbox')->lab('Firma Emaili')->width('200');
$_compinfo['tos_url'] = Setting::type('textbox')->lab('Hizmet Sözleşmesi Linki')->width('200')->desc(
    'Yeni kullanıcı kaydında, form altında çıkacak olan Hizmet Sözleşmesi linki'
)->predesc('http://');
$_compinfo['mail_signature'] = Setting::type('textarea')->lab('Email İmza')->width('200')->height(100)->desc(
    'Gönderilen emaillere eklenir'
);


$_comset['mail_method'] = Setting::type('combobox')->lab('Mail Gönderim Aracı')->opt('smtp', 'SMTP Hesabı ile')->opt(
    'phpmail',
    'PHP mail() Fonksiyonu'
)
        ->desc('SMTP ile gönderim yapmak için SMTP ayarlarını yapmış olmalısınız');

$_comset['smtp_server'] = Setting::type('textbox')->lab('SMTP Sunucu')->width('200')->depends('mail_method', 'smtp');
$_comset['smtp_username'] = Setting::type('textbox')->lab('SMTP Kullanıcı')->width('200')->depends(
    'mail_method',
    'smtp'
);
$_comset['smtp_password'] = Setting::type('textbox')->lab('SMTP Şifre')->width('200')->encrypted(true)->depends(
    'mail_method',
    'smtp'
);
$_comset['smtp_port'] = Setting::type('textbox')->lab('SMTP Port')->width('50')->desc('(Genelde: 25)')->depends(
    'mail_method',
    'smtp'
);
$_comset['smtp_ssl'] = Setting::type('checkbox')->lab('SMTP SSL')->desc(
    'Gmail gibi SSL üzerinden çalışan mail kullanıyorsanız işaretleyiniz'
)->depends('mail_method', 'smtp');


$_notify['notifymail'] = Setting::type('textbox')->lab('Uyarı Email Adresi')->width('200');
$_notify['notifycell'] = Setting::type('textbox')->lab('Uyarı GSM No')->width('200')->desc('+90532XXXYYZZ formatında');
$_notify['notifymsn'] = Setting::type('textbox')->lab('Uyarı MSN Adresi')->width('200')->desc(
    '(Bu emailin, MSN Botun MSN listesinde ekli olması gerekmektedir)'
);
$_notify['msnbot_email'] = Setting::type('textbox')->lab('MSN Bot Email')->width('200');
$_notify['msnbot_pass'] = Setting::type('textbox')->lab('MSN Bot Şifre')->width('200')->encrypted('1');
$_notify['ticket_assignment'] = Setting::type('checkbox')->lab('Bilet Atama')->desc(
    'Üzerine bilet atanan yöneticiyi MSN ile uyar'
);

$_notify['newclient'] = Setting::type('binary')->lab('Yeni Müşteri');
$_notify['neworder'] = Setting::type('binary')->lab('Yeni Sipariş');
$_notify['newticket'] = Setting::type('binary')->lab('Yeni Bilet');


$_payments['billgen'] = Setting::type('textbox')->lab('Borç oluşturma')->width('30')->predesc(
    'Sipariş bitiş tarihinden '
)->desc(' gün önce borç kaydı oluştur');
$_payments['remenabled'] = Setting::type('checkbox')->lab('Ödeme Uyarı Mailleri')->desc(
    'Aktif etmek için işaretleyiniz'
);
$_payments['rem1'] = Setting::type('textbox')->lab('1.Uyarı')->width('30')->desc(
    '(Bitiş tarihinden X gün önce, iptal için 0 giriniz)'
)->depends('remenabled', '1');
$_payments['rem2'] = Setting::type('textbox')->lab('2.Uyarı')->width('30')->desc(
    '(Bitiş tarihinden X gün önce, iptal için 0 giriniz)'
)->depends('remenabled', '1');
$_payments['rem3'] = Setting::type('textbox')->lab('3.Uyarı')->width('30')->desc(
    '(Bitiş tarihinden X gün önce, iptal için 0 giriniz)'
)->depends('remenabled', '1');
$_payments['rem4'] = Setting::type('textbox')->lab('4.Uyarı')->width('30')->desc(
    '(Bitiş tarihinden X gün önce, iptal için 0 giriniz)'
)->depends('remenabled', '1');


$_domains['remenabled'] = Setting::type('checkbox')->lab('Alan Adı Uyarı Mailleri')->desc(
    'Aktif etmek için işaretleyiniz'
);
$_domains['rem1'] = Setting::type('textbox')->lab('1.Uyarı')->width('30')->desc(
    '(Bitiş tarihinden X gün önce, iptal için 0 giriniz)'
)->depends('remenabled', '1');
$_domains['rem2'] = Setting::type('textbox')->lab('2.Uyarı')->width('30')->desc(
    '(Bitiş tarihinden X gün önce, iptal için 0 giriniz)'
)->depends('remenabled', '1');
$_domains['rem3'] = Setting::type('textbox')->lab('3.Uyarı')->width('30')->desc(
    '(Bitiş tarihinden X gün önce, iptal için 0 giriniz)'
)->depends('remenabled', '1');
$_domains['rem4'] = Setting::type('textbox')->lab('4.Uyarı')->width('30')->desc(
    '(Bitiş tarihinden X gün önce, iptal için 0 giriniz)'
)->depends('remenabled', '1');
$_domains['ns1'] = Setting::type('textbox')->lab('NS 1')->width('200');
$_domains['ns2'] = Setting::type('textbox')->lab('NS 2')->width('200');


$_tickets['limited'] = Setting::type('checkbox')->lab('Kısıtlı Destek')->desc(
    'Sadece belli müşterilerin bilet açabilmesi için tıklayınız'
);
$_tickets['limit_scope'] = Setting::type('combobox')->lab('Destek Verilecek Müşteriler')->opt(
    'active',
    'Aktif bir siparişi olanlar'
)->opt('selected', 'Sadece seçili servisleri satın alanlar');
$_tickets['filetypes'] = Setting::type('textbox')->lab('Dosya Uzantıları')->width('300')->desc(
    '(Yüklenmesine izin verilen dosya uzantıları, örn: doc,zip,rar)'
);
$_tickets['filesize'] = Setting::type('textbox')->lab('Maks Dosya Boyutu')->width('50')->desc(
    ' MB (Yüklenmesine izin verilen maksimum dosya boyutu)'
);


$_portal['status'] = Setting::type('combobox')->lab('Portal Durumu')->opt('active', 'Aktif')->opt(
    'maintenance',
    'Bakımda'
);
$_portal['tpl'] = Setting::type('function')->lab('Varsayılan Tema')->width('100')->callback(
    'getClientTemplates'
)->build();
$_portal['lang'] = Setting::type('function')->lab('Varsayılan Dil')->width('100')->callback('getLanguages')->build();
$_portal['force_required_fields'] = Setting::type('checkbox')->lab('Zorunlu alanlar')->desc(
    'Müşteri, bilgilerindeki zorunlu alanları güncellemeden paneli kullanamasın'
);

$_portal['seo'] = Setting::type('checkbox')->lab('SEO Desteği')->desc(
    'SEO Dostu URL\'ler kullanmak için işaretleyiniz (Ana dizindeki htaccess.txt dosyasının adını .htaccess olarak değiştirmeniz gerekmektedir)'
);


$_automation['suspend_by_balance'] = Setting::type('checkbox')->lab('Bakiye Durumu')->desc(
    'Müşterinin bakiyesi artıda ise, askıya alma işlemi yapma'
);

$_automation['suspend_enabled'] = Setting::type('checkbox')->lab('Askıya Alma')->desc(
    'Son ödeme tarihi geçmiş siparişleri otomatik askıya al'
);
$_automation['suspend_days'] = Setting::type('textbox')->lab('Askıya Alma Günü')->width('30')->desc(
    'Siparişleri son ödeme tarihinden X gün sonra askıya al'
);
$_automation['suspend_bills'] = Setting::type('checkbox')->lab('Askıya Alma')->desc(
    'Askıda olan siparişler için borç kaydı oluştur'
);

$_automation['terminate_enabled'] = Setting::type('checkbox')->lab('Hesap Kapama')->desc(
    'Son ödeme tarihi geçmiş siparişleri otomatik kapat ve sunucu üzerinden sil'
);
$_automation['terminate_days'] = Setting::type('textbox')->lab('Hesap Kapama Günü')->width('30')->desc(
    'Siparişleri son ödeme tarihinden X gün sonra kapat ve sunucu üzerinden sil'
);


$_automation['autoclose_enabled'] = Setting::type('checkbox')->lab('Süresi Biten Siparişler')->desc(
    'Süresi biten tek sefer ödemeli veya ücretsiz siparişleri otomatik kapat'
);


$_set = array(
    'compinfo' => $_compinfo,
    'commset' => $_comset,
    'notify' => $_notify,
    'portal' => $_portal,
    'tickets' => $_tickets,
    'payments' => $_payments,
    'domains' => $_domains,
    'automation' => $_automation
);
