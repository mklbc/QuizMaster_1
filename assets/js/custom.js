$(document).ready(function () {
  $(".stepBar .fill").css("width", "0");

  // Dinamik olarak tüm butonları dinle
  $("button[id^='step']").on("click", function () {
    var stepnumber = $(this).attr("id").replace("step", "").replace("btn", ""); // Adım numarasını al

    radiovalidate(stepnumber);

    if (checkedradio == false) {
      (function (el) {
        setTimeout(function () {
          el.children().remove(".reveal");
        }, 3000);
      })( 
        $("#error").append(
          '<div class="reveal alert alert-danger">Choose an option!</div>'
        )
      );
      radiovalidate(stepnumber);
    } else {
      // Eğer doğru cevap seçildiyse, animasyonları kaldır ve bir sonraki soruya geç
      $("#step" + stepnumber + " .optionInner").removeClass("animate");
      $("#step" + stepnumber + " .optionInner").addClass("animateReverse");
      setTimeout(function () {
        next();
      }, 900);
      // countresult(stepnumber); // Burada soru ile ilgili ek işlem yapılabilir
    }
  });

  // Submition işlemi
  $("#sub").on("click", function () {
    var lastStep = divs.length; // Son adımı bul
    radiovalidate(lastStep);

    if (checkedradio == false) {
      (function (el) {
        setTimeout(function () {
          el.children().remove(".reveal");
        }, 3000);
      })( 
        $("#error").append(
          '<div class="reveal alert alert-danger">Choose an option!</div>'
        )
      );
      radiovalidate(lastStep);
    } else {
      // Son soruya tıklanırsa, formu submit_exam.php sayfasına yönlendir
      window.location.href = "../submit_exam.php"; // Yönlendirme işlemi
    }
  });
});

var checkedradio = false;

function radiovalidate(stepnumber) {
  var checkradio = $("#step" + stepnumber + " input")
    .map(function () {
      if ($(this).is(":checked")) {
        return true;
      } else {
        return false;
      }
    })
    .get();

  checkedradio = checkradio.some(Boolean);
}

// next-prev işlevi
var divs = $(".show-section fieldset");
var Buttons = $(".nextPrev");
var now = 0; // şu anki gösterilen div
divs.hide().first().show(); // ilk soruyu göster
Buttons.hide().first().show();

// Gönder butonunun gizlendiğinden emin olalım
$("#sub").hide();

function next() {
  divs.eq(now).hide();
  now = now + 1 < divs.length ? now + 1 : 0;
  divs.eq(now).show(); // bir sonraki soruyu göster
  $(".stepBar .fill").css("width", now * (100 / divs.length) + "%"); // ilerleme çubuğu

  Buttons.hide().eq(now).show();
  
  // Son soruya gelindiğinde, Submit butonunu göster
  if (now === divs.length - 1) {
    // Son soruya gelindiğinde, "next" butonunun yerine "sub" butonunu göster
    //Buttons.hide(); // Önceki "next" butonlarını gizle
    $("#sub").show(); // Son adımda "sub" butonunu göster
  } else {
    // Diğer adımlarda "sub" butonunu gizle
    $("#sub").hide();
  }
}

$(".prev").on("click", function () {
  divs.eq(now).hide();
  now = now > 0 ? now - 1 : divs.length - 1;
  divs.eq(now).show(); // önceki soruyu göster
  Buttons.hide().eq(now).show();
  $(".stepBar .fill").css("width", now * (100 / divs.length) + "%");

  $(".optionInner").addClass("animate");
  $(".optionInner").removeClass("animateReverse");

  // Son soruya gelindiğinde, Submit butonunu göster
  if (now === divs.length - 1) {
    // Son soruya gelindiğinde, "next" butonunun yerine "sub" butonunu göster
    //Buttons.hide(); // Önceki "next" butonlarını gizle
    $("#sub").show(); // Son adımda "sub" butonunu göster
  } else {
    // Diğer adımlarda "sub" butonunu gizle
    $("#sub").hide();
  }
});
