

function formatTime(seconds) {
        let hours = Math.floor(seconds / 3600);
        let minutes = Math.floor((seconds % 3600) / 60);
        let remainingSeconds = seconds % 60;

        let formattedTime = '';

        if (hours > 0) {
            formattedTime += hours + ' giờ ';
        }

        if (minutes > 0) {
            formattedTime += minutes + ' phút ';
         }

        if (remainingSeconds > 0) {
            formattedTime += remainingSeconds + ' giây ';
        }

        return formattedTime.trim();
    }


function secondsToHMS(seconds) {
            let hours = Math.floor(seconds / 3600);
            let minutes = Math.floor((seconds % 3600) / 60);
            let remainingSeconds = seconds % 60;
            let formattedTime =
                (hours < 10 ? "0" : "") + hours + ":" +
                (minutes < 10 ? "0" : "") + minutes + ":" +
                (remainingSeconds < 10 ? "0" : "") + remainingSeconds;
            return formattedTime;
            }

            let countdownInterval;
            let isCountdownRunning = false;

  function startCountdown() {
            countdownInterval = setInterval(function() {
                isCountdownRunning = true;
                countdownValue--;
                document.getElementById('countdown').innerHTML = secondsToHMS(countdownValue);
                if (countdownValue <= 0) {
                    clearInterval(countdownInterval);
                          isCountdownRunning = false;
      Swal.fire({
        title: "Đã hết giờ làm bài",
        text: "Bạn sẽ nhận được kết quả và đáp án sau khi nộp bài !",
        icon: "warning",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        allowOutsideClick: false,
        confirmButtonText: "Nộp bài ngay"
      }).then((result) => {
        if (result.isConfirmed) {
          let timerInterval;
      Swal.fire({
        title: "Đang nộp bài thi!",
        html: "Vui lòng đợi trong giây lát.",
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
          submitButton();
          DoingTest = false;

        }
      }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
          console.log("Displayed Result");
          ResultInput()

     
              }
            });
              }
            });
                }
            }, 1000);

            }

            /*    function ResultInput() {
              // Copy the content to the form fields
              var contentToCopy1 = document.getElementById("final-result").textContent;
              var contentToCopy2 = document.getElementById("date").textContent;
              var contentToCopy3 = document.getElementById("time-result").textContent;
              var contentToCopy4 = document.getElementById("title").textContent;
              var contentToCopy5 = document.getElementById("id_category").textContent;
              var contentToCopy6 = document.getElementById("id_test").textContent;
          
              document.getElementById("resulttest").value = contentToCopy1;
              document.getElementById("dateform").value = contentToCopy2;
              document.getElementById("timedotest").value = contentToCopy3;
              document.getElementById("testname").value = contentToCopy4;
              document.getElementById("idcategory").value = contentToCopy5;
              document.getElementById("idtest").value = contentToCopy6;
          
             // Add a delay before submitting the form
    setTimeout(function() {
        // Automatically submit the form
        jQuery('#frmContactUs').submit();
    }, 5000); // 5000 milliseconds = 5 seconds
}*/