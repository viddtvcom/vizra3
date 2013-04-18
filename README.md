+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
 İçerik
+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
1) Sistem Gereklilikleri............(Requirements)
2) Kurulum Bilgileri................(Installation)
3) Yükseltme Bilgileri...................(Upgrade)
4) Sıkça Sorulan Sorular.....................(FAQ)

Kurulum Desteği için MSN adresimiz: destek@vizra.com
Diğer soru ve bilgi alma için http://forum.vizra.com adresindeki forumumuzu kullanabilirsiniz.


~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 1) Sistem Gereklilikleri
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
- Internet Bağlantısı
- PHP 5.0+
- MySQL 4.1+
- CURL / CURL SSL 
- IonCube
- Kurulum yaptığınız sunucunun dışarı çıkan 80.portu lisans doğrulaması için açık olmalıdır.  



~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 2) Kurulum Bilgileri
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
- Kuruluma başlamadan önce lisans anahtarınızı hazır bulundurunuz. Lisans anahtarınız yoksa http://panel.vizra.com/vizra-lisans.html adresinden 30 günlük ücretsiz deneme lisansı alabilirsiniz.

- "vizra" klasörü içindeki dosyaları, web sunucunuza FTP ile yükleyin.

- Aşağıdaki dizinlerin web sunucunuz tarafından yazılabilir olması gerekmektedir:
	engine/config/
	logs/
	tmp/
	tmp/smarty/
	uploads/ ve içindeki bütün dizinler

- Vizra'nın çalışması için bir MySQL veritabanına ihtiyacınız vardır. Kontrol panelinizi kullanarak bir veritabanı ve veritabanı kullanıcısı,şifresi oluşturun.

- Kurulum yaptığınız domainin alanadim.com, ve Vizra'yı kurduğunuz dizinin "vizra" olduğunu varsayarsak, http://www.alanadim.com/vizra/install adresini internet tarayıcınız ile çalıştırın. 

- Lisans anahtarınızı, veritabanı sunucu adresinizi (genelde localhost), veritabanı adı, veritabanı kullanıcı adı ve şifresini gerekli yerlere girdikten sonra kurulumu tamamlayın.

- Ekranda çıkan cron ayarlarınızı kontrol panelinizi kullanarak yapmanız gerekmektedir. Cron ayalarları hakkında bilgi almak için http://forum.vizra.com/forumdisplay.php?32 adresindeki açıklamaları takip edebilirsiniz. 

- Yönetim panelinize http://www.alanadim.com/vizra/acp adresinden ulaşabilirsiniz. İlk kurulumda yönetici emaili olarak admin@vizra.com ve şifre olarak admin ile giriş yapabilirsiniz.


~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 3) Yükseltme Bilgileri
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
- Yükseltme işlemine başlamadan önce mutlaka config.php ve MySQL veritabanınızın yedeğini aldığınızdan emin olun.

- "vizra" klasörü altındaki bütün dosyaları mevcut dosyalarınızın üzerine FTP ile yazın.

- Kurulum yaptığınız domainin alanadim.com, ve Vizra'yı kurduğunuz dizinin "vizra" olduğunu varsayarsak, http://www.alanadim.com/vizra/install adresini internet tarayıcınız ile çalıştırın. Sistem sizi otomatik olarak yükseltme bölümüne yönlendirecektir.

- Mevcut sürüm numarasını kontrol ettikten sonra "Yükselt" butonunu tıklayın.


~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 4) Sıkça Sorulan Sorular
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

S: Kurulumda "Site error: the file XXXX requires the ionCube PHP Loader ioncube_loader_XXXX to be installed by the site administrator" hatası alıyorum.

C: Sisteminizde IONCUBE yüklü değil. IONCUBE neredeyse standart olarak bütün hosting sunucularında kurulu bulunmaktadır. Eğer sunucu kendinizin ise http://www.ioncube.com/loaders.php adresinden ilgili dosyaları sunucunuza kurmalısınız. Sunucu sizin değilse, hosting hizmet aldığınız firmadan IONCUBE yüklenmesiniz talep edebilirsiniz.


S: Kurulumu tamamladım ama "License Error: License not found" hatası alıyorum. 

C: Kurulum sırasında geçerli bir lisans anahtarı kullanmadınız veya lisans anahtarınız aktif değil. Son olarak Vizra'nın kurulu bulunduğu sunucunun dışarı çıkan 80.port lisans doğrulaması için açık olmalıdır. Bunu hosting hizmeti aldığınız firma ile kontrol ediniz.

	
