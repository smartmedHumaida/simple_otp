<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

     <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body
    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <button class="bg-white" style="padding: 10px" onclick="window.location='/'"><- Back</button>
            <div
                class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
                <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                    <div
                        class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                        <h1 class="mb-1 font-medium">Creative Assistance OTP Test</h1>
                        <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">Enter the code sent to your WhatsApp</p>

                        <ul class="flex flex-col mb-4 lg:mb-6">
                            <li
                                class="flex items-center gap-4 py-2 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:top-1/2 before:bottom-0 before:left-[0.4rem] before:absolute">
                                <span class="relative py-1 bg-white dark:bg-[#161615]">
                                    <span
                                        class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                                        <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                                    </span>
                                </span>
                                <span>
                                    OTP Code
                                    <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">You can resend in 1 minute</p>
                                    <br>
                                    <div style="display: flex; align-items: center;">
                                        <input id="otp" type="text"
                                            style="border: 1px solid white; padding: 10px; width: 250px"
                                            placeholder="Enter 6-digit code">
                                    </div>
                                    <p id="error-msg" style="color: red; margin-top: 8px; display: none;"></p>
                                </span>
                            </li>
                        </ul>

                        <ul class="flex gap-3 text-sm leading-normal">
                            <li>
                                <button onclick="verifyOtp()" type="button"
                                    class="cursor-pointer inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal">
                                    Verify
                                </button>
                            </li>
                        </ul>
                    </div>
                </main>
            </div>
            <script>
    async function verifyOtp() {
        const otp = document.getElementById('otp').value;
        const errorMsg = document.getElementById('error-msg');

        errorMsg.style.display = 'none';

        console.log('fetching...');

        const response = await fetch('/api/otp/verify', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'accept': 'application/json' },
            body: JSON.stringify({ otp })
        })
            .catch((error) => {
                console.log('error happened');
            })


        const data = await response.json();
        console.log(data);

        if (response.ok) {
            console.log('redirecting...');
            
            window.location.href = '/dashboard'; // change to wherever after success
        } else {
           alert('Invalid')
        }
    }
</script>
</body>


</html>