<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="shortcut icon" type="image/jpeg" href="favicon.png" />
<title>Opencart Translator</title>
</head>

<body>
<h1>Opencart Translator</h1>
<p>Operchart Translator adalah sebuah tool untuk menterjemahkan Opencart ke dalam bahasa lain. Program ini memerlukan sebuah bahasa referensi, misalnya bahasa Inggris. Proram akan menampilkan semua file yang ada di dalam paket bahasa kemudian menampilkan isinya. Setiap file dapat berisi beberapa <em>key</em>. Berdasarkan <em>key</em> inilah program diterjemahkan.</p>
<h3>Cara Menggunakan</h3>
<p>Untuk menterjemahkan Opencart ke dalam bahasa lain, masukkan file paket bahasa ke dalam direktori &quot;upload&quot;.</p>
<p>Contoh:</p>
<ul>
  <li>upload</li>
  <ul>
    <li>catalog</li>
    <ul>
      <li>language</li>
      <ul>
        <li>english</li>
        <li>indonesia</li>
      </ul>
    </ul>
    <li>admin</li>
    <ul>
      <li>language</li>
      <ul>
        <li>english</li>
        <li>indonesia</li>
      </ul>
    </ul>
  </ul>
</ul>
<p>Direktori bahasa ada 2 yaitu &quot;catalog&quot; dan &quot;admin&quot;. Pada masing-masing direktori terdapat file dan subdirektori. Apabila pengguna mengklik sebuah file yang ada di dalam direktori maupun subdirektori, program akan menampilkan semua <em>key</em> yang ada di dalam file tersebut. Apabila pengguna menterjemahkan <em>key</em> tersebut, program secara otomatis akan menambahkan <em>key</em> yang belum ada atau mengubah nilainya jika sudah ada.</p>
<p><img src="image1.png" width="798" height="651" alt="Image 1"></p>
<p><em>Key</em> yang tidak ada pada bahasa tujuan akan ditandai dengan bintang (*). Apabila pengguna mengklik file yang tidak lengkap, program akan secara otomatis mencari <em>key</em> pertama yang tidak ada pada bahasa tujuan. Dengan demikian, pengguna akan lebih cepat menemukannya. Untuk berpindah ke <em>key</em> selanjutnya yang tidak ada pada bahasa tujuan, pengguna dapat memilih tombol &quot;Next&quot;.</p>
<p>Program dapat membandingkan bahasa tujuan dengan bahasa referensi. Apabila program tidak menemukan <em>key</em> pada bahasa tujuan, maka program akan memberitahukan kepada pengguna. Dengan demikian, pengguna dapat lebih mudah menemukan file yang tidak lengkap.</p>
<p><img src="image2.png" width="798" height="651" alt="Image 2"></p>
</body>
</html>