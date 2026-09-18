const currentRemaining = 590; // 09:50 remaining
let localEndTime = new Date().getTime() + 590000;
let lastRemaining = 590;

console.log("Timer before pause shows: 09:50");

// Simulate 14 second pause
setTimeout(() => {
    console.log("Resuming after 14 seconds...");
    // Server sends 590 (because passed time is cancelled out by leftover)
    const newCurrentRemaining = 590;

    // OLD JS LOGIC:
    if (localEndTime === null || lastRemaining !== newCurrentRemaining) {
        console.log("Condition met!");
        if (localEndTime === null) {
            localEndTime = new Date().getTime() + (newCurrentRemaining * 1000);
        } else {
            const currentLocalRemaining = (localEndTime - new Date().getTime()) / 1000;
            if (Math.abs(currentLocalRemaining - newCurrentRemaining) > 3) {
                localEndTime = new Date().getTime() + (newCurrentRemaining * 1000);
            }
        }
        lastRemaining = newCurrentRemaining;
    } else {
        console.log("Condition NOT met! lastRemaining === currentRemaining");
    }

    const now = new Date().getTime();
    const distance = localEndTime - now;
    
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    console.log(`Timer now shows: ${minutes}:${seconds}`);

}, 14000);
