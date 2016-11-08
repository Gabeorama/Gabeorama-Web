counters = [];

function createCountdown(element) {
    //Elements
    var daysE = element.getElementsByClassName("days")[0];
    var hoursE = element.getElementsByClassName("hours")[0];
    var minutesE = element.getElementsByClassName("minutes")[0];
    var secondsE = element.getElementsByClassName("seconds")[0];

    var days = parseInt(daysE.innerHTML);
    var hours = parseInt(hoursE.innerHTML);
    var minutes = parseInt(minutesE.innerHTML);
    var seconds = parseInt(secondsE.innerHTML);

    var counter = {
        "days": [days, daysE],
        "hours": [hours, hoursE],
        "minutes": [minutes, minutesE],
        "seconds": [seconds, secondsE]
    };

    counters.push(counter);
}

function updateTimers() {
    counters.forEach(updateTimer);
}

function updateTimer(timer) {
    if (timer["seconds"][0]-- <= 0) {
        if (timer["minutes"][0]-- <= 0) {
            if (timer["hours"][0]-- <= 0) {
                if (timer["days"][0]-- < 0) {
                    timer["days"][1].innerHTML = "Sometime in the past";
                    timer["hours"][1].innerHTML = "";
                    timer["minutes"][1].innerHTML = "";
                    timer["seconds"][1].innerHTML = "";
                    return;
                } else {
                    timer["days"][1].innerHTML = timer["days"][0];
                    timer["hours"][0] = 23;
                }
            }
            timer["minutes"][0] = 59;
        }

        timer["seconds"][0] = 59;
    }
    timer["hours"][1].innerHTML = (timer["hours"][0] < 10 ? "0" : "") + timer["hours"][0];
    timer["minutes"][1].innerHTML = (timer["minutes"][0] < 10 ? "0" : "") + timer["minutes"][0];
    timer["seconds"][1].innerHTML = (timer["seconds"][0] < 10 ? "0" : "") + timer["seconds"][0];

}

function hide(id) {
    var element = document.getElementById("kino-" + id);
    var button = document.getElementById("showHide-" + id);
    var active = button.innerHTML == "-";
    console.log(active);

    if (active) {
        element.style.display = "none";
        button.innerHTML = "+";
    } else {
        element.style.display = "";
        button.innerHTML = "-";
    }
}

function show(id) {
    var element = document.getElementById("kino-" + id);
    var button = document.getElementById("showHide-" + id);

    element.style.display = "";
    button.innerHTML = "-";
    button.onclick = hide(id);
}

elements = document.getElementsByClassName("countDown");
for (var i = 0; i < elements.length; i++) {
    createCountdown(elements[i]);
}

setInterval(updateTimers, 1000);