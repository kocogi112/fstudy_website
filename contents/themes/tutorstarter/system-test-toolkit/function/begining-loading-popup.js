function showLoadingPopup() {
  let timerInterval;

  Swal.fire({
      title: "<p>Bạn sẵn sàng làm bài chưa</p>", // thêm các module tickbox ở đây
          //text:"",
          //html: "<p>Bạn sẵn sàng làm bài chưa</p>",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Bắt đầu làm bài",
            //cancelButtonText:"Hủy"
          }).then((result) => {
            if (result.isConfirmed) {
              if (result.isConfirmed) {
          let timerInterval;
      Swal.fire({
        title: "Đang tải bài thi",
        html: "Vui lòng đợi trong giây lát",
        timer: 2000,
        allowOutsideClick: false,
          showCloseButton: false,


        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading();
          const timer = Swal.getPopup().querySelector("b");
          timerInterval = setInterval(() => {
            
          }, 100);
        },
        willClose: () => {
          clearInterval(timerInterval);
          

        }
      }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
          console.log("Test displayed");
          startTest();
          DoingTest = true;
          MathJax.Hub.Queue(["Typeset",MathJax.Hub]);

        }
      });
      }

            }
          });







              }

          
function closePopup() {
  document.getElementById("loading-popup-remember").style.display = "none";
}

function startTest() {
  document.getElementById("title").style.display = "none";
  document.getElementById("navi-button").style.display = 'block';
  document.getElementById("checkbox-button").style.display = 'block';

  document.getElementById("change_appearance").style.display = "block";
  document.getElementById("start-test").style.display = 'none';
  document.getElementById("basic-info").style.display = 'none';
  document.getElementById("quiz-container").style.display = 'block';
  document.getElementById("submit-button").style.display = 'block';
  document.getElementById("current_module").style.display = "block";
  var explain_zone = document.getElementsByClassName("explain-zone");
  for (var i = 0; i < explain_zone.length; i++)
      explain_zone[i].style.display = 'none';
  document.getElementById("center-block").style.display = 'block';

  document.getElementById('countdown').innerHTML =  secondsToHMS(countdownValue);
  startCountdown();
  

  showQuestion(currentQuestionIndex);
 
}