# WEB TASARIMI PROJE RAPORU

## Proje Başlığı
**Kütüphane Otomasyonu Sistemi**

## Hazırlayan
**Ad Soyad:** Enes Arda Canlı  
**Öğrenci Numarası:** [Öğrenci Numarası]  
**Bölüm / Program:** [Bölüm / Program]  
**Ders Adı:** [Ders Adı]  
**Öğretim Elemanı:** [Öğretim Elemanı]  
**Teslim Tarihi:** [Teslim Tarihi]

---

## İÇİNDEKİLER
1. Giriş
2. Projenin Amacı
3. Projenin Kapsamı
4. Kullanılan Teknolojiler
5. Site Yapısı ve Sayfa Planı
6. Tasarım Özellikleri
7. HTML Yapısı
8. CSS Yapısı
9. PHP Kullanımı
10. Responsive Tasarım ve Tarayıcı Uyumluluğu
11. Karşılaşılan Sorunlar ve Çözümleri
12. Sonuç
13. Kaynakça
14. Ekler

---

## 1. GİRİŞ
Bu projede HTML, CSS, PHP ve MySQL kullanılarak kapsamlı bir "Kütüphane Otomasyonu Sistemi" geliştirilmiştir. Proje kapsamında hem kütüphane yöneticileri/personelleri hem de normal üyeler için kullanıcı dostu, düzenli, işlevsel ve görsel açıdan modern (dark/light tema destekli) bir web arayüzü oluşturulması amaçlanmıştır. Web sitesi hazırlanırken temel web standartlarına dikkat edilmiş, MVC (Model-View-Controller) mimarisine uygun bir dosya yapısı planlanmış ve farklı cihazlarda (mobil, tablet, masaüstü) sorunsuz çalışabilmesi için responsive (duyarlı) tasarım özenle uygulanmıştır.

## 2. PROJENİN AMACI
Bu projenin amacı, web tasarımı ve programlama konularını uygulamalı olarak kullanarak gerçek hayattaki bir problemi çözen (kütüphane süreçlerinin dijitalleşmesi) işlevsel bir web uygulaması geliştirmektir. Proje ile birlikte:
- **Semantic HTML** yapısını doğru kullanmak,
- **CSS** ile (özel temalar, flexbox, değişkenler) estetik ve düzenli bir tasarım oluşturmak,
- **PHP ve MySQL** ile dinamik içerik, rol tabanlı erişim kontrolü, veri ekleme/silme ve form işlemleri gerçekleştirmek,
- **Responsive** tasarım mantığını uygulamak,
- Kod düzeni, modülerlik ve tarayıcı uyumluluğu konusunda en iyi pratikleri (Clean Code) deneyimlemek hedeflenmektedir.

## 3. PROJENİN KAPSAMI
Bu proje kapsamında geliştirilen web uygulaması, rol tabanlı erişime (Yönetici, Personel, Üye) göre yetkilendirilmiş aşağıdaki bölümlerden oluşmaktadır:
- **Giriş (Login) Ekranı:** Kullanıcı doğrulama ve oturum yönetimi.
- **Kitaplar:** Sisteme kayıtlı kitapların listelenmesi, detaylarının görüntülenmesi, yeni kitap ekleme ve silme işlemleri.
- **Kütüphanem:** Üyelerin kendi favori kitaplarını ve okuma listelerini görebildiği alan.
- **Üyeler:** Kütüphaneye kayıtlı kullanıcıların yönetimi (Yalnızca yetkili roller için).
- **Ödünç İşlemleri:** Kitap alım ve iade süreçlerinin takip edildiği ve yönetildiği sistem.
- **Rezervasyonlar:** Üyelerin kitapları ayırtabildiği ve personelin bu rezervasyonları onayladığı alan.
- **PHP ile Dinamik İşlemler:** Veritabanı sorguları (PDO), CRUD (Oluştur, Oku, Güncelle, Sil) işlemleri, dinamik yetkilendirme ve sepet/rezervasyon akışı.

## 4. KULLANILAN TEKNOLOJİLER
Bu projede aşağıdaki teknolojiler kullanılmıştır:
- **HTML5:** Web sayfalarının iskelet yapısını ve form elemanlarını oluşturmak için kullanılmıştır.
- **CSS3 (Vanilla & Variables):** Sayfanın görsel tasarımı, Dark/Light tema geçişleri, animasyonlar, hover efektleri ve responsive yapı için kullanılmıştır.
- **PHP 8:** Form işlemleri, oturum (session) yönetimi, veritabanı bağlantısı, rol kontrolleri ve MVC yapısında controller/model yönetimi için kullanılmıştır.
- **MySQL:** Kitaplar, kullanıcılar, ödünç kayıtları ve rezervasyonlar gibi dinamik verileri saklamak için veritabanı olarak kullanılmıştır.
- **Ek Araçlar / Teknolojiler:**
  - **Bootstrap 5.3:** Temel grid sistemi, form yapıları ve modal'lar için (CSS ile özelleştirilerek) tercih edilmiştir.
  - **Google Fonts:** "Inter" yazı tipi kullanılarak modern bir tipografi sağlanmıştır.
  - **Phosphor Icons:** Modern, sade ve estetik vektörel ikonlar için kullanılmıştır.
  - **JavaScript:** Sidebar daraltma/genişletme ve Dark/Light mod teması geçişlerindeki dinamik DOM manipülasyonu için kullanılmıştır.

## 5. SİTE YAPISI VE SAYFA PLANI
Bu projede MVC (Model-View-Controller) mimarisine yakın modüler bir yapı kullanılmıştır. Sitenin genel yapısı aşağıdaki gibidir:

| Sayfa Adı | Açıklama |
| :--- | :--- |
| **Login (Giriş) Sayfası** | Kullanıcıların sisteme giriş yaptığı sayfadır. Sidebar (yan menü) burada gizlidir. |
| **Kitaplar S. (`?action=books`)** | Sitedeki tüm kitapların grid veya tablo halinde listelendiği, filtrelendiği sayfadır. |
| **Kütüphanem (`?action=my_library`)** | Kullanıcıya özel, favoriye alınan veya takip edilen kitapların bulunduğu alandır. |
| **Üyeler (`?action=users`)** | Yetkililerin, sisteme kayıtlı üyeleri listeleyip düzenlediği sayfadır. |
| **Ödünç İş. (`?action=loans`)** | Hangi kitabın kimde olduğunun ve iade sürelerinin takip edildiği sayfadır. |
| **Rezervasyonlar** | Üyelerin ayırtma işlemlerinin (rezervasyonların) onaylanıp yönetildiği alandır. |

**Basit Site Haritası:**
Login ➝ Ana Layout ➝ (Kitaplar / Kütüphanem / Üyeler / Ödünç İşlemleri / Rezervasyonlar)

## 6. TASARIM ÖZELLİKLERİ
Web sitesinin tasarımında "Glassmorphism" esintileri taşıyan, modern, yüksek performanslı ve kullanıcı dostu bir yaklaşım benimsenmiştir. Renk seçimlerinde CSS değişkenleri (`:root`) kullanılarak hem **Koyu (Dark)** hem de **Açık (Light)** tema desteği eklenmiştir.
Tasarımda dikkat edilen noktalar:
- **Tema Motoru:** CSS Değişkenleri ile pürüzsüz (0.45s transition) karanlık ve aydınlık mod geçişi sağlandı.
- **Menü Yapısı:** Yan menü (Sidebar) olarak tasarlandı, dar ekranlarda ve istendiğinde küçültülebilen yapı (Collapsible Sidebar) entegre edildi.
- **Modern Tipografi:** Okunabilirliği yüksek *Inter* fontu, hiyerarşik font boyutları kullanıldı.
- **Hover ve Mikro Animasyonlar:** Butonlara, kartlara ve tablo satırlarına estetik `hover` ve `transform` efektleri eklendi.

## 7. HTML YAPISI
Projede HTML yapısı oluşturulurken Semantic (anlamsal) etiketlerin kullanımına dikkat edilmiştir:
- Yan menü için `<nav>`, ana içerik alanı için `<main>` etiketleri kullanıldı.
- Başlık yapıları düzenli kullanılmıştır (`<h1>`, `<h2>`). 
- **Tablolar:** `<thead>`, `<tbody>`, `<tr>`, `<th>` ve `<td>` yapıları doğru şekilde listeleme sayfalarında (Örn: Üyeler, Ödünç Listesi) kullanıldı.
- **Form Elemanları:** Kitap ekleme veya kullanıcı girişi alanlarında, her `input` ve `select` elementi için açıklayıcı `<label>` etiketleri eklendi.
- **İkonlar:** Phosphor ikon seti `<svg>` veya `<i>` elementleri ile semantik olarak yerleştirildi.

## 8. CSS YAPISI
CSS kodları modülerlik açısından `layout.php` içerisinde global stiller olarak konumlandırılmış, renk ve gölge yönetimi tamamen **CSS Değişkenleri (Variables)** ile yapılmıştır.
Kullanılan temel CSS özellikleri şunlardır:
- **`data-theme` Değişkenleri:** Koyu ve açık tema için `--bg-primary`, `--text-primary`, `--accent` vb. renk paletleri tanımlandı.
- **Flexbox:** Sayfa içi öğelerin hizalanması, Sidebar'ın dikey yerleşimi için sıklıkla kullanıldı.
- **Transitions:** `var(--transition-speed)` ile sayfadaki kartların, butonların ve temaların pürüzsüz değişmesi sağlandı.
- **Responsive Medya Sorguları (`@media`):** Ekran boyutu `768px` altına düştüğünde Sidebar'ın alt kısıma sabitlenmesi / mobilde uyumlu hale gelmesi sağlandı.
- **Özelleştirilmiş Scrollbar:** Web tarayıcı kaydırma çubuğu, tasarımla bütünlük sağlaması için özelleştirildi (`::-webkit-scrollbar`).

## 9. PHP KULLANIMI
Sistem baştan aşağı PHP ile dinamik hale getirilmiştir:
- **MVC Benzeri Yapı:** `index.php` (Router), `controllers/` klasörü (İş mantığı) ve `views/` (Görünüm) şeklinde sayfalar ayrıldı.
- **Oturum ve Yetkilendirme:** `$_SESSION` kullanılarak giriş yapan kullanıcının rolü (Admin, Staff, Member) kontrol edildi ve yetkisiz sayfalara erişim engellendi.
- **Veritabanı PDO:** `db.php` aracılığıyla veritabanına güvenli bağlantı sağlandı (SQL Injection koruması ile).
- **Dinamik Veri Gösterimi:** Kitap listeleri, rezervasyon durumları veritabanından çekilerek PHP `foreach` döngüleriyle tablolara ve kartlara basıldı.

## 10. RESPONSIVE TASARIM VE TARAYICI UYUMLULUĞU
Sistem tüm cihazlarda uyumlu çalışacak şekilde geliştirilmiştir:
- Masaüstü cihazlarda sol tarafta geniş veya daraltılabilir bir "Sidebar" menü yer alır.
- Mobil cihazlarda ve tabletlerde menü tam genişlikte yatay veya toggle menüye dönüşerek alan tasarrufu sağlar.
- Tablolar, `.table-responsive` sınıfları sayesinde dar ekranlarda kaydırılabilir hale getirilerek taşmalar engellenmiştir.
Sistem modern tarayıcılarda (Google Chrome, Firefox, Safari, Edge) test edilmiş ve uyumlu şekilde çalıştığı gözlemlenmiştir.

## 11. KARŞILAŞILAN SORUNLAR VE ÇÖZÜMLERİ
| Karşılaşılan Sorun | Çözüm |
| :--- | :--- |
| **Filtre / Arama Kutusu kenarlığının kalması** | Arama input'undaki `border` css özelliği `transparent` yapılarak ve `:focus` durumu yeniden yazılarak düzeltildi. |
| **Menü alanının mobilde sayfa içeriğini itmesi** | Medya sorgusu (`@media (max-width: 768px)`) ile Sidebar'ın `position` değeri güncellenip yatay yapılandırıldı. |
| **Rol Bazlı İçerik Gösteriminde Hata** | PHP tarafında `in_array()` ve katı eşitlik (`===`) kullanılarak admin/personel kontrolleri sabitlendi. |
| **Geçiş (Tema) Sırasında Ekranın Titremesi (FOUC)** | Tema tercihi `localStorage` üzerinden sayfa yüklenmeden (inline JS) okunarak sayfanın yanlış temada başlaması engellendi. |

## 12. SONUÇ
Bu proje ile HTML, CSS, PHP ve MySQL kullanılarak profesyonel, modüler ve yüksek performanslı bir Kütüphane Otomasyonu geliştirilmiştir. Proje sürecinde;
- Temiz kod (Clean Code) standartlarının önemi kavranmış,
- Role-based Access Control (Rol tabanlı erişim kontrolü) mimarisi öğrenilmiş,
- Veritabanı ve arayüz entegrasyonu başarılı bir şekilde sağlanmıştır.
Proje, kütüphanecilik işlemlerini önemli ölçüde hızlandıracak, kullanıcı deneyimini (Dark/Light mod, modern arayüz) üst düzeye çıkaracak yetkinliktedir.

## 13. KAYNAKÇA
- **MDN Web Docs:** HTML, CSS, JavaScript Referansları. (https://developer.mozilla.org/)
- **PHP Documentation:** PHP Veritabanı (PDO) ve Session kullanımları. (https://www.php.net/manual/tr/)
- **Bootstrap v5.3:** Grid ve temel UI bileşenleri. (https://getbootstrap.com/)
- **Phosphor Icons:** Açık kaynaklı ikon kütüphanesi. (https://phosphoricons.com/)
- **Google Fonts:** Inter Yazı tipi ailesi. (https://fonts.google.com/)

## 14. EKLER
*(Bu bölüm Word belgesine eklendikten sonra tarafınızdan ekran görüntüleri eklenmelidir.)*
- Giriş (Login) Ekranı Görseli
- Kütüphane Ana Sayfası / Kitaplar Listesi Görseli
- Tema Geçişi (Açık ve Koyu Mod) Karşılaştırmalı Görseli
- Ödünç veya Rezervasyon Ekranı
- Veritabanı (`database.sql`) tablosundan küçük bir şema / görüntü.
