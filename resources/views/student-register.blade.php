
@extends('layout.website')
@section('title', 'Student Register')
@section('content')

    @php
        $setting = App\Models\RegistrationSetting::first();
    @endphp
<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">

    <!-- Registration Form Wrapper -->
    <form id="enrollmentForm" action="{{ route('student-register.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        <!-- Hidden Input for Base64 Face Image -->
        <input type="hidden" name="face_image" id="faceImageInput">
        <input type="hidden" name="face_embedding" id="faceEmbeddingInput">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

            <!-- RIGHT COLUMN: Face Scanner Viewport -->
            <div class="lg:col-span-7" id="scannerDiv">
                <div class="glass-panel-glow rounded-2xl p-6 relative overflow-hidden ring-1 ring-white/5 shadow-2xl shadow-black/40">

                    <!-- Header & Status Bar -->
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-brand-400 animate-pulse"></div>
                            <h2 class="text-sm font-bold tracking-wide uppercase text-slate-200">AI Biometric
                                Scanner</h2>
                        </div>
                        <span id="modelStatusBadge"
                            class="text-[11px] font-semibold px-3 py-1.5 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>Loading Neural Models...</span>
                        </span>
                    </div>

                    <!-- Live Camera Viewport Container -->
                    <div
                        class="relative w-full aspect-[4/3] rounded-xl overflow-hidden bg-slate-950 border border-slate-800/80 flex items-center justify-center shadow-inner ring-1 ring-black/50">

                        <!-- Video Feed -->
                        <video id="webcam" autoplay playsinline muted
                            class="w-full h-full object-cover hidden transform -scale-x-100"></video>

                        <!-- Canvas Overlay for faceapi box rendering -->
                        <canvas id="overlayCanvas"
                            class="absolute inset-0 w-full transform -scale-x-100 h-full pointer-events-none z-10"></canvas>

                        <!-- Biometric Scanning HUD Frame Overlay -->
                        <div id="hudOverlay"
                            class="absolute inset-0 pointer-events-none flex flex-col justify-between p-6 z-20 opacity-40 transition-opacity duration-300">
                            <div class="flex justify-between w-full h-12">
                                <div class="w-12 h-12 hud-corner-tl"></div>
                                <div class="w-12 h-12 hud-corner-tr"></div>
                            </div>

                            <!-- Scanning Line (Animated) -->
                            <div id="scanningLine"
                                class="hidden absolute left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-cyber-accent to-transparent shadow-[0_0_15px_#17a51c] animate-scan-line">
                            </div>

                            <div class="flex justify-between w-full h-12">
                                <div class="w-12 h-12 hud-corner-bl"></div>
                                <div class="w-12 h-12 hud-corner-br"></div>
                            </div>
                        </div>

                        <!-- Placeholder State (Camera Offline / Pre-load) -->
                        <div id="cameraPlaceholder"
                            class="flex flex-col items-center justify-center space-y-3 z-30 p-6 text-center">
                            <div
                                class="w-16 h-16 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-500 ring-1 ring-white/5">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm font-semibold text-slate-300">Camera Feed Inactive</p>
                                <p class="text-xs text-slate-500 max-w-xs leading-relaxed">Initialize camera after neural models
                                    finish loading.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Real-time Validation Message Banner -->
                    <div id="validationBanner"
                        class="mt-5 p-3.5 rounded-xl bg-slate-950/60 border border-slate-800 flex items-center justify-between text-xs font-medium text-slate-400 ring-1 ring-white/5">
                        <div class="flex items-center space-x-2">
                            <span id="statusIndicatorDot" class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            <span id="statusMessage" class="text-slate-300">Initialising model weights...</span>
                        </div>
                        <span id="faceCountBadge" class="font-mono text-[10px] text-slate-500 uppercase tracking-wider px-2 py-1 rounded-md bg-slate-900/60 border border-slate-800/60">Faces:
                            0</span>
                    </div>

                    <!-- Controls Bar -->
                    <div class="mt-5 flex flex-col sm:flex-row gap-3">
                        <button type="button" id="startCamBtn" disabled
                            class="flex-1 py-3 px-4 rounded-xl font-semibold text-xs uppercase tracking-wider text-slate-400 bg-slate-900 border border-slate-800 hover:border-slate-700 hover:text-slate-200 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span>Start Camera</span>
                        </button>

                        <button type="button" id="captureBtn" disabled
                            class="flex-1 py-3 px-4 rounded-xl font-semibold text-xs uppercase tracking-wider text-white bg-gradient-to-r from-brand-500 to-brand-400 border border-brand-500/50 shadow-lg shadow-brand-500/20 hover:shadow-brand-500/30 disabled:opacity-40 disabled:shadow-none disabled:bg-slate-800 disabled:from-slate-800 disabled:to-slate-800 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Capture Biometric</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- LEFT COLUMN: Student Information Form -->
            <div class="lg:col-span-5 space-y-6">
                <div class="glass-panel rounded-2xl p-6 sm:p-7 shadow-2xl shadow-black/40 ring-1 ring-white/5 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-brand-500/10 rounded-full blur-3xl pointer-events-none">
                    </div>

                    <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-800/80">
                        <div class="p-2.5 rounded-xl bg-gradient-to-br from-slate-800 to-slate-900 text-brand-400 ring-1 ring-white/5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-white tracking-tight">Student Details</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Input primary academic information</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">
                                Full Name <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" id="studentName" name="name" required
                                placeholder="e.g. Alexander Pierce"
                                class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-600 outline-none hover:border-slate-700 focus:border-brand-500/60 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200">
                            <span class="text-[11px] text-rose-400 mt-1 block" id="nameError"></span>
                        </div>

                        <!-- Roll / Student ID -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">
                                Roll / Student ID <span class="text-rose-400">*</span>
                            </label>
                            <input id="rollNumber" type="text" name="roll" required placeholder="e.g. CS-2026-089"
                                class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-600 outline-none hover:border-slate-700 focus:border-brand-500/60 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200">
                            <span class="text-[11px] text-rose-400 mt-1 block" id="rollError"></span>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">
                                Institutional Email <span class="text-rose-400">*</span>
                            </label>
                            <input id="studentEmail" type="email" name="email" required
                                placeholder="a.pierce@university.edu"
                                class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-600 outline-none hover:border-slate-700 focus:border-brand-500/60 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200">
                            <span class="text-[11px] text-rose-400 mt-1 block" id="emailError"></span>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">
                                Phone Number
                            </label>
                            <input type="tel" name="phone" id="studentPhone" placeholder="+1 (555) 019-2834"
                                class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-600 outline-none hover:border-slate-700 focus:border-brand-500/60 focus:ring-2 focus:ring-brand-500/20 transition-all duration-200">
                            <span class="text-[11px] text-rose-400 mt-1 block" id="phoneError"></span>
                        </div>
                    </div>
                </div>

                <!-- CAPTURED IMAGE PREVIEW CARD -->
                <div id="capturedPreviewCard"
                    class="glass-panel rounded-2xl p-5 border border-emerald-500/20 shadow-lg shadow-emerald-500/5 transition-all duration-300 hidden">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Face Scan Recorded
                        </span>
                        <button type="button" id="retakeBtn"
                            class="text-[11px] font-medium text-slate-400 hover:text-white underline underline-offset-2 transition-colors duration-200">
                            Retake Scan
                        </button>
                    </div>

                    <div
                        class="relative rounded-xl overflow-hidden border border-emerald-500/30 bg-slate-950 aspect-video flex items-center justify-center ring-1 ring-black/40">
                        <img id="capturedImage" class="w-full h-full object-cover" alt="Captured Face Preview">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/10 to-transparent">
                        </div>
                        <div
                            class="absolute bottom-2.5 left-3 right-3 flex justify-between items-center text-[11px] font-medium text-slate-300">
                            <span>Biometric Vector Ready</span>
                            <span class="text-emerald-400 font-mono font-semibold">100% Quality</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitFormBtn" disabled
                    class="w-full py-3.5 px-6 rounded-xl font-semibold text-sm text-slate-500 bg-slate-900 border border-slate-800 cursor-not-allowed disabled:opacity-70 transition-all duration-200 shadow-inner flex items-center justify-center space-x-2">
                    <span>Complete Registration</span>
                </button>
            </div>


        </div>
    </form>
</main>
@endsection
@push('css')

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f6ff',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            900: '#0c4a6e',
                        },
                        cyber: {
                            bg: '#090d16',
                            card: 'rgba(15, 23, 42, 0.75)',
                            border: 'rgba(56, 189, 248, 0.2)',
                            accent: '#17a51c'
                        }
                    },
                    animation: {
                        'scan-line': 'scan 2.5s linear infinite',
                        'pulse-glow': 'pulseGlow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        scan: {
                            '0%': {
                                top: '0%'
                            },
                            '50%': {
                                top: '95%'
                            },
                            '100%': {
                                top: '0%'
                            },
                        },
                        pulseGlow: {
                            '0%, 100%': {
                                opacity: '0.4'
                            },
                            '50%': {
                                opacity: '0.8'
                            },
                        }
                    }
                }
            }
        }
    </script>

    <!-- face-api.js (Loaded without defer to prevent 'faceapi is not defined' issue) -->
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>
    <!-- CDN Libraries -->

    <!-- CDN Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/onnxruntime-web/dist/ort.min.js"></script>

    <!-- MediaPipe Tasks Vision (ES Module Way) -->
    <script type="module">
        import {
            FilesetResolver,
            FaceLandmarker
        } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14/vision_bundle.mjs";

        // Make them globally available to non-module scripts
        window.FilesetResolver = FilesetResolver;
        window.FaceLandmarker = FaceLandmarker;
    </script>

    <style>
        /* Glassmorphism custom styling */
        .glass-panel {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-panel-glow {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(56, 189, 248, 0.3);
            box-shadow: 0 0 25px -5px rgba(14, 165, 233, 0.15);
        }

        /* Biometric Frame HUD lines */
        .hud-corner-tl {
            border-top: 3px solid #ffffff;
            border-left: 3px solid #ffffff;
        }

        .hud-corner-tr {
            border-top: 3px solid #ffffff;
            border-right: 3px solid #ffffff;
        }

        .hud-corner-bl {
            border-bottom: 3px solid #ffffff;
            border-left: 3px solid #ffffff;
        }

        .hud-corner-br {
            border-bottom: 3px solid #ffffff;
            border-right: 3px solid #ffffff;
        }
    </style>
@endpush

@push('js')
    <script>
        // ==========================================
        // 1. DOM ELEMENTS & APPLICATION STATE
        // ==========================================
        const video = document.getElementById('webcam');
        const overlayCanvas = document.getElementById('overlayCanvas');
        const startCamBtn = document.getElementById('startCamBtn');
        const captureBtn = document.getElementById('captureBtn');
        const submitFormBtn = document.getElementById('submitFormBtn');
        const modelStatusBadge = document.getElementById('modelStatusBadge');
        const validationBanner = document.getElementById('validationBanner');
        const statusMessage = document.getElementById('statusMessage');
        const statusIndicatorDot = document.getElementById('statusIndicatorDot');
        const faceCountBadge = document.getElementById('faceCountBadge');
        const cameraPlaceholder = document.getElementById('cameraPlaceholder');
        const hudOverlay = document.getElementById('hudOverlay');
        const scanningLine = document.getElementById('scanningLine');
        const faceImageInput = document.getElementById('faceImageInput');
        const capturedPreviewCard = document.getElementById('capturedPreviewCard');
        const capturedImage = document.getElementById('capturedImage');
        const retakeBtn = document.getElementById('retakeBtn');

        // System State
        let faceLandmarker = null;
        let arcfaceSession = null;
        let isCameraRunning = false;
        let mediaStream = null;
        let animationFrameId = null;

        // Biometric Processing Buffers
        let currentBase64Image = null;
        let currentEmbedding = null;

        // Settings & Thresholds
        const ARCFACE_MODEL_URL = "{{ asset('models/arcface.onnx') }}"; // আপনার লোকাল public/models পাথ
        const SIMILARITY_THRESHOLD = {{ $setting->similarity_threshold }};

        // ==========================================
        // STEP 1 — LOAD REQUIRED MODELS
        // ==========================================
        async function initModels() {
            try {
                updateStatus('AI Engines Initializing...', 'amber');

                // 1. Load MediaPipe Face Landmarker WASM & Model
                const vision = await FilesetResolver.forVisionTasks(
                    "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14/wasm"
                );

                faceLandmarker = await FaceLandmarker.createFromOptions(vision, {
                    baseOptions: {
                        modelAssetPath: `https://storage.googleapis.com/mediapipe-models/face_landmarker/face_landmarker/float16/1/face_landmarker.task`,
                        delegate: "GPU" // GPU না থাকলে MediaPipe অটোমেটিক CPU তে ফলব্যাক করবে
                    },
                    runningMode: "VIDEO",
                    numFaces: 2,
                    outputFaceBlendshapes: true
                });

                // 2. Load ArcFace ONNX Model with Execution Provider Fallback
                ort.env.wasm.numThreads = Math.min(4, navigator.hardwareConcurrency || 2);

                // Fallback array: WebGL না চললে WASM, না চললে CPU ব্যবহার করবে
                arcfaceSession = await ort.InferenceSession.create(ARCFACE_MODEL_URL, {
                    executionProviders: ['webgl', 'wasm', 'cpu']
                });

                // UI Readiness
                modelStatusBadge.className =
                    "text-xs px-2.5 py-1 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center space-x-1.5";
                modelStatusBadge.innerHTML =
                    `<span class="w-2 h-2 rounded-full bg-emerald-400"></span><span>AI Engines Ready</span>`;

                startCamBtn.disabled = false;
                startCamBtn.className =
                    "flex-1 py-3 px-4 rounded-xl font-medium text-xs uppercase tracking-wider text-slate-900 bg-cyber-accent hover:bg-cyan-300 transition duration-200 flex items-center justify-center space-x-2 shadow-lg shadow-cyber-accent/20 cursor-pointer";

                updateStatus('Camera offline. Click "Start Camera".', 'amber');
            } catch (error) {
                console.error("AI Models Load Error Detail:", error);
                modelStatusBadge.className =
                    "text-xs px-2.5 py-1 rounded-md bg-rose-500/10 text-rose-400 border border-rose-500/20";
                modelStatusBadge.innerText = "Model Load Failed";
                updateStatus(`Failed to load AI models (${error.message || 'Check Console'}).`, 'rose');
            }

            startCamera();
        }

        // ==========================================
        // STEP 2 — INITIALIZE CAMERA
        // ==========================================
        async function startCamera() {
            if (!faceLandmarker || !arcfaceSession) return;

            try {
                mediaStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: {
                            ideal: 640
                        },
                        height: {
                            ideal: 480
                        },
                        facingMode: 'user'
                    },
                    audio: false
                });

                video.srcObject = mediaStream;

                video.onloadedmetadata = () => {
                    video.play();
                    video.classList.remove('hidden');
                    cameraPlaceholder.classList.add('hidden');
                    hudOverlay.classList.remove('opacity-40');
                    hudOverlay.classList.add('opacity-100');
                    scanningLine.classList.remove('hidden');
                    isCameraRunning = true;

                    startCamBtn.disabled = true;
                    startCamBtn.className =
                        "flex-1 py-3 px-4 rounded-xl font-medium text-xs uppercase tracking-wider text-slate-500 bg-slate-900 border border-slate-800 cursor-not-allowed flex items-center justify-center space-x-2";

                    processVideoFrame();
                };
            } catch (err) {
                console.error("Camera Access Error: ", err);
                updateStatus('Camera access denied or device unavailable.', 'rose');
            }
        }

        // ==========================================
        // STEP 3 & 4 — DETECTION LOOP & VALIDATION
        // ==========================================
        let lastVideoTime = -1;

        async function processVideoFrame() {
            if (!isCameraRunning) return;

            if (video.currentTime !== lastVideoTime) {
                lastVideoTime = video.currentTime;
                const results = await faceLandmarker.detectForVideo(video, performance.now());

                // Prep Canvas Context
                overlayCanvas.width = video.videoWidth;
                overlayCanvas.height = video.videoHeight;
                const ctx = overlayCanvas.getContext('2d');
                ctx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);

                // Realtime Validation Loop
                validateAndRenderHUD(results, ctx);
            }

            animationFrameId = requestAnimationFrame(processVideoFrame);
        }

        function validateAndRenderHUD(results, ctx) {
            const numFaces = results.faceLandmarks ? results.faceLandmarks.length : 0;
            faceCountBadge.innerText = `Faces: ${numFaces}`;

            // Validation 1: Face Count
            if (numFaces === 0) {
                disableCaptureBtn();
                updateStatus('No face detected. Step in front of the camera.', 'amber');
                return;
            }

            if (numFaces > 1) {
                disableCaptureBtn();
                updateStatus('Multiple faces detected. Only one person must be visible.', 'rose');
                return;
            }

            const landmarks = results.faceLandmarks[0];
            const blendshapes = results.faceBlendshapes[0].categories;
            const imgWidth = video.videoWidth;
            const imgHeight = video.videoHeight;

            // Calculate Bounding Box
            let minX = imgWidth,
                minY = imgHeight,
                maxX = 0,
                maxY = 0;
            landmarks.forEach(pt => {
                const x = pt.x * imgWidth;
                const y = pt.y * imgHeight;
                if (x < minX) minX = x;
                if (x > maxX) maxX = x;
                if (y < minY) minY = y;
                if (y > maxY) maxY = y;
            });

            const boxWidth = maxX - minX;
            const boxHeight = maxY - minY;
            const centerX = minX + boxWidth / 2;
            const centerY = minY + boxHeight / 2;

            // Validation 2: Position (Center Box check - 20% margin around center)
            if (centerX < imgWidth * 0.25) {
                drawBoundingBox(ctx, minX, minY, boxWidth, boxHeight, '#c20a0a');
                disableCaptureBtn();
                updateStatus('Move slightly to the Right', 'amber');
                return;
            }
            if (centerX > imgWidth * 0.75) {
                drawBoundingBox(ctx, minX, minY, boxWidth, boxHeight, '#c20a0a');
                disableCaptureBtn();
                updateStatus('Move slightly to the Left', 'amber');
                return;
            }
            if (centerY < imgHeight * 0.2) {
                drawBoundingBox(ctx, minX, minY, boxWidth, boxHeight, '#c20a0a');
                disableCaptureBtn();
                updateStatus('Move slightly Down', 'amber');
                return;
            }
            if (centerY > imgHeight * 0.8) {
                drawBoundingBox(ctx, minX, minY, boxWidth, boxHeight, '#c20a0a');
                disableCaptureBtn();
                updateStatus('Move slightly Up', 'amber');
                return;
            }

            // Validation 3: Face Size (Optimal coverage: 20% - 60% of frame area)
            const faceAreaRatio = (boxWidth * boxHeight) / (imgWidth * imgHeight);
            if (faceAreaRatio < 0.12) {
                drawBoundingBox(ctx, minX, minY, boxWidth, boxHeight, '#c20a0a');
                disableCaptureBtn();
                updateStatus('Move closer to the camera', 'amber');
                return;
            }
            if (faceAreaRatio > 0.65) {
                drawBoundingBox(ctx, minX, minY, boxWidth, boxHeight, '#c20a0a');
                disableCaptureBtn();
                updateStatus('Move farther from the camera', 'amber');
                return;
            }

            // Validation 4: Lighting & Brightness
            // const brightness = calculateFrameBrightness(ctx, minX, minY, boxWidth, boxHeight);
            // if (brightness < 10) {
            //     drawBoundingBox(ctx, minX, minY, boxWidth, boxHeight, '#f59e0b');
            //     disableCaptureBtn();
            //     updateStatus('Too dark! Please improve room lighting.', 'amber');
            //     return;
            // }
            // if (brightness > 210) {
            //     drawBoundingBox(ctx, minX, minY, boxWidth, boxHeight, '#f59e0b');
            //     disableCaptureBtn();
            //     updateStatus('Too bright! Reduce strong background light.', 'amber');
            //     return;
            // }

            // Validation 5: Eye Status (Blink detection via blendshapes)
            const eyeBlinkLeft = blendshapes.find(b => b.categoryName === 'eyeBlinkLeft')?.score || 0;
            const eyeBlinkRight = blendshapes.find(b => b.categoryName === 'eyeBlinkRight')?.score || 0;
            if (eyeBlinkLeft > 0.5 || eyeBlinkRight > 0.5) {
                drawBoundingBox(ctx, minX, minY, boxWidth, boxHeight, '#c20a0a');
                disableCaptureBtn();
                updateStatus('Please keep both eyes open.', 'amber');
                return;
            }

            // Validation 6: Head Pose / Yaw angle check using landmark distance ratio
            const leftEye = landmarks[33];
            const rightEye = landmarks[263];
            const nose = landmarks[1];
            const distLeft = Math.abs(nose.x - leftEye.x);
            const distRight = Math.abs(rightEye.x - nose.x);
            const yawRatio = distLeft / (distRight + 0.0001);

            if (yawRatio < 0.4 || yawRatio > 2.2) {
                drawBoundingBox(ctx, minX, minY, boxWidth, boxHeight, '#c20a0a');
                disableCaptureBtn();
                updateStatus('Please look straight at the camera.', 'amber');
                return;
            }

            // ALL VALIDATIONS PASSED SUCCESSFUL!
            drawBoundingBox(ctx, minX, minY, boxWidth, boxHeight, '#17a51c');
            enableCaptureBtn();
            updateStatus('Face aligned perfectly. Ready to capture!', 'emerald');
        }

        // Helper: Brightness calculation in bounding box
        function calculateFrameBrightness(ctx, x, y, w, h) {
            try {
                const imageData = ctx.getImageData(x, y, w, h);
                const data = imageData.data;
                let sum = 0;
                for (let i = 0; i < data.length; i += 16) { // Sample every 4th pixel
                    sum += (data[i] * 0.299 + data[i + 1] * 0.587 + data[i + 2] * 0.114);
                }
                return sum / (data.length / 16);
            } catch (e) {
                return 128; // fallback average
            }
        }

        // Custom Corner Accent Bounding Box UI
        function drawBoundingBox(ctx, x, y, w, h, color) {
            ctx.strokeStyle = color;
            ctx.lineWidth = 2;
            ctx.strokeRect(x, y, w, h);

            const l = 12;
            ctx.strokeStyle = '#ffffff';
            ctx.lineWidth = 3;

            // Top-Left
            ctx.beginPath();
            ctx.moveTo(x, y + l);
            ctx.lineTo(x, y);
            ctx.lineTo(x + l, y);
            ctx.stroke();
            // Top-Right
            ctx.beginPath();
            ctx.moveTo(x + w - l, y);
            ctx.lineTo(x + w, y);
            ctx.lineTo(x + w, y + l);
            ctx.stroke();
            // Bottom-Left
            ctx.beginPath();
            ctx.moveTo(x, y + h - l);
            ctx.lineTo(x, y + h);
            ctx.lineTo(x + l, y + h);
            ctx.stroke();
            // Bottom-Right
            ctx.beginPath();
            ctx.moveTo(x + w - l, y + h);
            ctx.lineTo(x + w, y + h);
            ctx.lineTo(x + w, y + h - l);
            ctx.stroke();
        }

        // ==========================================
        // STEP 5, 6 & 7 — CAPTURE, ALIGN & EMBEDDING
        // ==========================================
        captureBtn.addEventListener('click', async () => {
            if (!isCameraRunning) return;

            updateStatus('Processing & generating embedding...', 'amber');
            disableCaptureBtn();

            // Detect final landmarks
            const results = await faceLandmarker.detectForVideo(video, performance.now());
            if (!results.faceLandmarks || results.faceLandmarks.length === 0) {
                updateStatus('Face lost right at capture. Try again.', 'rose');
                return;
            }

            const landmarks = results.faceLandmarks[0];

            // Step 6: Alignment & Normalization
            const alignedCanvas = alignAndCropFace(video, landmarks);

            // Save preview base64
            currentBase64Image = alignedCanvas.toDataURL('image/jpeg', 0.92);
            faceImageInput.value = currentBase64Image;
            capturedImage.src = currentBase64Image;

            // Hide the scanner
            document.getElementById('scannerDiv').classList.add('hidden');

            // Step 7: Generate 512-dim ArcFace Embedding
            try {
                currentEmbedding = await generateArcFaceEmbedding(alignedCanvas);

                // Step 8: Check for Duplicates in existing Database
                const isDuplicate = await verifyDuplicateFace(currentEmbedding);
                if (isDuplicate) {
                    updateStatus('Registration Rejected: This face is already registered!', 'rose');
                    submitFormBtn.disabled = true;
                    return;
                }

                // Successfully processed
                capturedPreviewCard.classList.remove('hidden');
                updateStatus('Face successfully verified and embedding ready!', 'emerald');

                submitFormBtn.disabled = false;
                submitFormBtn.className =
                    "w-full py-3.5 px-6 rounded-xl font-medium text-sm text-white bg-gradient-to-r from-brand-600 to-cyan-600 hover:from-brand-500 hover:to-cyan-500 cursor-pointer transition duration-200 shadow-lg shadow-brand-500/25 flex items-center justify-center space-x-2";

                stopCamera();
            } catch (err) {
                console.error("Embedding generation failed:", err);
                updateStatus('Biometric feature extraction failed. Try again.', 'rose');
            }
        });

        // Step 6 Helper: Align face based on eyes orientation and crop to 112x112 (ArcFace Standard)
        function alignAndCropFace(videoElement, landmarks) {
            const imgW = videoElement.videoWidth;
            const imgH = videoElement.videoHeight;

            const leftEye = {
                x: landmarks[33].x * imgW,
                y: landmarks[33].y * imgH
            };
            const rightEye = {
                x: landmarks[263].x * imgW,
                y: landmarks[263].y * imgH
            };

            // Eye angle rotation
            const dy = rightEye.y - leftEye.y;
            const dx = rightEye.x - leftEye.x;
            const angle = Math.atan2(dy, dx);

            // Center between eyes
            const eyeCenter = {
                x: (leftEye.x + rightEye.x) / 2,
                y: (leftEye.y + rightEye.y) / 2
            };

            const canvas = document.createElement('canvas');
            canvas.width = 112;
            canvas.height = 112;
            const ctx = canvas.getContext('2d');

            ctx.save();
            // Translate to origin & scale/rotate
            ctx.translate(56, 45); // Standard ArcFace eye target alignment
            ctx.rotate(-angle);

            const eyeDistance = Math.hypot(dx, dy);
            const scale = 38 / eyeDistance; // Target eye distance ratio
            ctx.scale(scale, scale);

            // Draw original video flipped back horizontally
            ctx.translate(-eyeCenter.x, -eyeCenter.y);
            ctx.drawImage(videoElement, 0, 0);
            ctx.restore();

            return canvas;
        }

        // Step 7 Helper: ArcFace ONNX Preprocessing & Inference
        async function generateArcFaceEmbedding(alignedCanvas) {
            const ctx = alignedCanvas.getContext('2d');
            const imgData = ctx.getImageData(0, 0, 112, 112);
            const {
                data
            } = imgData;

            // Tensor formatting: Shape [1, 3, 112, 112] Normalized (-1 to 1)
            const float32Data = new Float32Array(1 * 3 * 112 * 112);
            for (let i = 0; i < 112 * 112; i++) {
                const r = data[i * 4];
                const g = data[i * 4 + 1];
                const b = data[i * 4 + 2];

                // CHW layout normalization: (x - 127.5) / 128.0
                float32Data[i] = (r - 127.5) / 128.0;
                float32Data[112 * 112 + i] = (g - 127.5) / 128.0;
                float32Data[2 * 112 * 112 + i] = (b - 127.5) / 128.0;
            }

            const inputTensor = new ort.Tensor('float32', float32Data, [1, 3, 112, 112]);
            const feeds = {
                [arcfaceSession.inputNames[0]]: inputTensor
            };
            const outputMap = await arcfaceSession.run(feeds);
            const outputTensor = outputMap[arcfaceSession.outputNames[0]];

            // Return L2 Normalized 512-dim embedding
            return l2Normalize(Array.from(outputTensor.data));
        }

        function l2Normalize(vector) {
            const norm = Math.sqrt(vector.reduce((sum, val) => sum + val * val, 0));
            return vector.map(val => val / (norm + 1e-10));
        }

        // ==========================================
        // STEP 8 — DUPLICATE FACE VERIFICATION
        // ==========================================
        async function verifyDuplicateFace(newEmbedding) {
            try {
                const response = await fetch('/students/embeddings');
                if (!response.ok) return false;

                const registeredStudents = await response.json();

                for (const student of registeredStudents) {
                    if (!student.embedding) continue;

                    const storedEmbedding = typeof student.embedding === 'string' ? JSON.parse(student.embedding) :
                        student.embedding;
                    const similarity = cosineSimilarity(newEmbedding, storedEmbedding);

                    if (similarity >= SIMILARITY_THRESHOLD) {
                        const similarityPercentage = Math.round(similarity * 100) + '%';

                        stopCamera(); // ক্যামেরা আগে বন্ধ করুন

                        showAlert('Duplicate Found!',
                            `This face is already registered as "${student.name} (${student.roll})" (Match: ${similarityPercentage})`,
                            'error','{{ route("home") }}');

                        return true; // Match found
                    }
                }
            } catch (err) {
                console.error("Error checking duplicate face: ", err);
            }
            return false;
        }

        function cosineSimilarity(embA, embB) {
            let dotProduct = 0;
            for (let i = 0; i < embA.length; i++) {
                dotProduct += embA[i] * embB[i];
            }
            return dotProduct; // Already L2 normalized, so dot product equals cosine similarity
        }

        // ==========================================
        // STEP 9 — SUBMIT DATA VIA FORM / AJAX
        // ==========================================
        submitFormBtn.addEventListener('click', async (e) => {
            e.preventDefault();

            if (!currentEmbedding || !currentBase64Image) {
                alert("Please capture your face before submitting.");
                return;
            }

            // Input Elements
            const studentNameInput = document.getElementById('studentName');
            const rollNumberInput = document.getElementById('rollNumber');
            const studentEmailInput = document.getElementById('studentEmail');
            const studentPhoneInput = document.getElementById('studentPhone');

            // Validation

            // 1. Name Validation
            if (studentNameInput.value.trim().length < 1) {
                document.getElementById('nameError').innerHTML = 'Please enter your name';
                studentNameInput.focus();
                return;
            } else {
                document.getElementById('nameError').innerHTML = '';
            }

            // 2. Roll Validation
            if (rollNumberInput.value.trim().length < 1) {
                document.getElementById('rollError').innerHTML = 'Please enter your roll number';
                rollNumberInput.focus();
                return;
            } else {
                document.getElementById('rollError').innerHTML = '';
            }


            const payload = {
                name: document.getElementById('studentName')?.value || '',
                roll: document.getElementById('rollNumber')?.value || '',
                email: document.getElementById('studentEmail')?.value || '',
                phone: document.getElementById('studentPhone')?.value || '',
                image: currentBase64Image,
                embedding: JSON.stringify(currentEmbedding) // Send as 512-array
            };

            try {
                updateStatus('Verifying duplicate face in system...', 'amber');
                const isDuplicate = await verifyDuplicateFace(currentEmbedding);
                if (isDuplicate) {
                    updateStatus('Registration Rejected: Face already exists!', 'rose');
                    return; // সাবমিট বন্ধ করে দিবে
                }
                const res = await fetch('/student-register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    updateStatus('Student registered successfully!', 'emerald');
                    // capturedPreviewCard.classList.add('hidden')
                    showAlert('Success', 'Registration success. Please wait for active the account!','success','{{ route("home") }}');
                    document.getElementById('enrollmentForm').reset();
                    submitFormBtn.disabled = true;
                } else {
                    const errData = await res.json();
                    updateStatus(`Server Error: ${errData.message || 'Submission failed'}`, 'rose');
                    showAlert('Error', 'Registration closed', 'error','{{ route("home") }}');
                }
            } catch (err) {
                showAlert('Error', 'Registration closed', 'error','{{ route("home") }}');
                updateStatus('Network error while submitting data.', 'rose');
            }
        });

        // Retake logic
        retakeBtn.addEventListener('click', () => {
            capturedPreviewCard.classList.add('hidden');
            document.getElementById('scannerDiv').classList.remove('hidden');
            faceImageInput.value = '';
            currentEmbedding = null;

            submitFormBtn.disabled = true;
            submitFormBtn.className =
                "w-full py-3.5 px-6 rounded-xl font-medium text-sm text-white bg-slate-800 border border-slate-700 cursor-not-allowed transition duration-200 shadow-lg flex items-center justify-center space-x-2";

            startCamera();
        });

        // ==========================================
        // UTILITIES & LIFECYCLE
        // ==========================================
        function stopCamera() {
            if (animationFrameId) cancelAnimationFrame(animationFrameId);
            if (mediaStream) mediaStream.getTracks().forEach(track => track.stop());

            isCameraRunning = false;
            video.classList.add('hidden');
            cameraPlaceholder.classList.remove('hidden');
            scanningLine.classList.add('hidden');
            hudOverlay.classList.add('opacity-40');

            disableCaptureBtn();
            startCamBtn.disabled = false;
            startCamBtn.className =
                "flex-1 py-3 px-4 rounded-xl font-medium text-xs uppercase tracking-wider text-slate-900 bg-cyber-accent hover:bg-cyan-300 transition duration-200 flex items-center justify-center space-x-2 shadow-lg shadow-cyber-accent/20 cursor-pointer";
        }

        function enableCaptureBtn() {
            captureBtn.disabled = false;
            captureBtn.className =
                "flex-1 py-3 px-4 rounded-xl font-medium text-xs uppercase tracking-wider text-slate-900 bg-emerald-400 hover:bg-emerald-300 transition duration-200 flex items-center justify-center space-x-2 shadow-lg shadow-emerald-400/20 cursor-pointer";
        }

        function disableCaptureBtn() {
            captureBtn.disabled = true;
            captureBtn.className =
                "flex-1 py-3 px-4 rounded-xl font-medium text-xs uppercase tracking-wider text-white bg-slate-800 border border-slate-700 cursor-not-allowed transition duration-200 flex items-center justify-center space-x-2";
        }

        function updateStatus(msg, theme) {
            statusMessage.innerText = msg;
            if (theme === 'emerald') {
                statusIndicatorDot.className = "w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_#10b981]";
                validationBanner.className =
                    "mt-4 p-3 rounded-xl bg-emerald-950/40 border border-emerald-500/30 flex items-center justify-between text-xs font-medium text-emerald-300";
            } else if (theme === 'rose') {
                statusIndicatorDot.className = "w-2 h-2 rounded-full bg-rose-500 shadow-[0_0_8px_#f43f5e]";
                validationBanner.className =
                    "mt-4 p-3 rounded-xl bg-rose-950/40 border border-rose-500/30 flex items-center justify-between text-xs font-medium text-rose-300";
            } else {
                statusIndicatorDot.className = "w-2 h-2 rounded-full bg-amber-400 animate-ping";
                validationBanner.className =
                    "mt-4 p-3 rounded-xl bg-slate-900/90 border border-slate-800 flex items-center justify-between text-xs font-medium text-slate-300";
            }
        }

        // Attach Lifecycle Listeners
        startCamBtn.addEventListener('click', startCamera);
        window.addEventListener('DOMContentLoaded', initModels);
        window.addEventListener('beforeunload', stopCamera);
    </script>
@endpush
