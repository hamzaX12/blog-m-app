// JavaScript/jQuery code for handling the steps and navigation
$(document).ready(function () {
    var currentStep = 0;
    var totalSteps = $(".step").length;

    function showStep(stepNumber) {
        $(".step").hide();
        $(".step:eq(" + stepNumber + ")").show();
    }

    function updateButtons() {
        if (currentStep === 0) {
            $(".prev").hide();
        } else {
            $(".prev").show();
        }

        if (currentStep === totalSteps - 1) {
            $(".next").hide();
            $(".submit").show();
        } else {
            $(".next").show();
            $(".submit").hide();
        }
    }

    updateButtons();

    $(".next").click(function () {
        if (currentStep < totalSteps - 1) {
            currentStep++;
            showStep(currentStep);
            updateButtons();
        }
    });

    $(".prev").click(function () {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
            updateButtons();
        }
    });

    $(".submit").click(function () {
        // Check if all steps have been completed
        var allStepsCompleted = true;
        $(".step").each(function () {
            var $step = $(this);
            if ($step.find(":input:radio:not(:checked)").length > 0) {
                allStepsCompleted = false;
                return false; // Exit the loop
            }
        });

        if (allStepsCompleted) {
            // Submit the form
            $("form").submit();
        } else {
            alert("Please complete all steps before submitting.");
        }
    });
});
