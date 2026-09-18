const currentRemaining = 590; 
let localEndTime = new Date().getTime() + 590000;

console.log("Timer before pause shows: 09:50");

setTimeout(() => {
    console.log("Resuming after 14 seconds...");
    const newCurrentRemaining = 590;

    const currentLocalRemaining = (localEndTime - new Date().getTime()) / 1000;
    if (Math.abs(currentLocalRemaining - newCurrentRemaining) > 10) {
        console.log("Difference is > 10! Updating localEndTime.");
        localEndTime = new Date().getTime() + (newCurrentRemaining * 1000);
    }
    
    const distance = localEndTime - new Date().getTime();
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    console.log(`Timer now shows: ${minutes}:${seconds}`);
}, 14000);
