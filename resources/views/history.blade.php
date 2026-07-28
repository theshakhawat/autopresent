@extends('layout.website')
@section('title', 'Attendance History')
@section('content')

    @php
        $setting = App\Models\RegistrationSetting::first();
    @endphp
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1  gap-6 items-start">

            <!-- LEFT: SCANNER -->
            <div class="lg:col-span-7" id="scannerDiv">
                <div class="glass rounded-2xl p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-accent animate-pop"></span>
                            <h2 class="text-sm font-display font-semibold uppercase tracking-wide">Attendance History Scanner
                            </h2>
                        </div>
                        <span id="modelStatusBadge"
                            class="text-[11px] px-2.5 py-1 rounded-md bg-alertSoft text-alert border border-alert/25 font-mono">Loading
                            models…</span>
                    </div>

                    <div
                        class="relative w-full aspect-[4/3] rounded-xl overflow-hidden bg-surface2 border border-border flex items-center justify-center">
                        <video id="webcam" autoplay playsinline muted class="w-full h-full object-cover transform -scale-x-100 hidden"></video>
                        <canvas id="overlayCanvas"
                            class="absolute inset-0 w-full h-full transform -scale-x-100 pointer-events-none z-10"></canvas>

                        <!-- radar sweep ring, signature scanning element -->
                        <div id="radarWrap"
                            class="hidden absolute inset-0 z-20 pointer-events-none items-center justify-center">
                            <div class="w-[78%] aspect-square rounded-full radar-ring animate-sweep opacity-70"></div>
                        </div>

                        <div id="cameraPlaceholder"
                            class="flex flex-col items-center justify-center space-y-3 z-30 p-6 text-center">
                            <div
                                class="w-14 h-14 rounded-full bg-surface border border-border flex items-center justify-center text-muted">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-muted">Requesting camera access…</p>
                        </div>
                    </div>

                    <div id="validationBanner"
                        class="mt-4 p-3 rounded-xl bg-surface2 border border-border flex items-center justify-between text-xs font-medium text-muted">
                        <div class="flex items-center gap-2">
                            <span id="statusIndicatorDot" class="w-2 h-2 rounded-full bg-alert"></span>
                            <span id="statusMessage">Initialising model weights…</span>
                        </div>
                        <span id="faceCountBadge" class="font-mono text-[10px] text-muted uppercase">Faces: 0</span>
                    </div>

                    <div class="mt-4 flex gap-3">
                        <button type="button" id="restartCamBtn"
                            class="py-3 px-4 rounded-xl font-medium text-xs uppercase tracking-wider text-muted bg-surface2 border border-border hover:border-accent/40 hover:text-accent transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M4 4v5h5M20 20v-5h-5M5.5 9A7 7 0 0119 11M18.5 15A7 7 0 015 13" />
                            </svg>
                            Restart
                        </button>
                        <button type="button" id="captureBtn" disabled
                            class="flex-1 py-3 px-4 rounded-xl font-semibold text-xs uppercase tracking-wider text-white bg-border cursor-not-allowed transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Capture &amp; Verify</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- RIGHT: RESULT + LOG -->
            <div class="lg:col-span-5 space-y-6">

                <!-- WAITING STATE -->
                <div id="waitingCard"
                    class="glass rounded-2xl p-6 text-center flex flex-col items-center justify-center min-h-[220px]">
                    <div
                        class="w-12 h-12 rounded-full bg-accentSoft border border-accent/25 flex items-center justify-center text-accent mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-ink">Waiting for scan</p>
                    <p class="text-xs text-muted mt-1 max-w-[220px]">Align your face in the frame and press Capture
                        &amp; Verify.</p>
                </div>

                <!-- MATCH FOUND CARD (id badge style) -->
                <div id="matchCard" class="hidden glass rounded-2xl overflow-hidden animate-badgeIn">
                    <div class="bg-verifySoft border-b border-verify/25 px-5 py-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-verify" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-xs font-semibold uppercase tracking-wider text-verify">Identity
                            Verified</span>
                    </div>
                    <div class="p-5 flex gap-4 items-center">
                        <img id="matchPhoto" class="w-16 h-16 rounded-xl object-cover border border-border bg-surface2"
                            alt="Matched face">
                        <div class="flex-1 min-w-0">
                            <p id="matchName" class="font-display font-semibold text-base leading-tight truncate">—
                            </p>
                            <p id="matchRoll" class="text-md font-mono text-muted mt-0.5">—</p>
                            <p id="matchEmail" class="text-xs text-muted truncate mt-0.5">—</p>
                            <p id="matchPhone" class="text-xs text-muted truncate mt-0.5">—</p>
                        </div>
                    </div>
                    <div class="px-5 pb-2">
                        <!-- Label & Percentage Header -->
                        <div class="flex items-center justify-between text-[11px] font-mono text-gray-500 mb-1.5">
                            <span>Confidence</span>
                            <span id="matchConfidence"
                                class="font-semibold transition-colors duration-300 text-gray-400">—%</span>
                        </div>

                        <!-- Progress Bar Track -->
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden dark:bg-gray-700">
                            <!-- Dynamic Progress Bar Fill -->
                            <div id="confidenceBar"
                                class="bg-gray-400 h-2 rounded-full transition-all duration-500 ease-out"
                                style="width: 0%;">
                            </div>
                        </div>
                    </div>


                    <div class="p-5 pt-3 flex gap-3">
                        <button type="button" id="retakeBtn"
                            class="py-3 px-4 rounded-xl text-xs font-medium uppercase tracking-wider text-muted bg-surface2 border border-border hover:text-ink transition">Rescan</button>
                        <button type="button" id="presentBtn"
                            class="flex-1 py-3 px-4 rounded-xl text-xs font-semibold uppercase tracking-wider text-white bg-verify hover:opacity-90 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Show Attendance History
                        </button>
                    </div>
                </div>

                <!-- NO MATCH CARD -->
                <div id="noMatchCard" class="hidden glass rounded-2xl overflow-hidden animate-badgeIn">
                    <div class="bg-dangerSoft border-b border-danger/25 px-5 py-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="text-xs font-semibold uppercase tracking-wider text-danger">Not Recognized</span>
                    </div>
                    <div class="p-5">
                        <p class="text-sm text-muted">No matching record was found in the database for this face. Make
                            sure the person is registered, or try scanning again with better lighting.</p>
                    </div>
                    <div class="p-5 pt-0">
                        <button type="button" id="retryBtn"
                            class="w-full py-3 px-4 rounded-xl text-xs font-semibold uppercase tracking-wider text-ink bg-surface2 border border-border hover:border-accent/40 transition">Try
                            Again</button>
                    </div>
                </div>

                <div id="attendanceHistoryCard" class="glass hidden rounded-2xl overflow-hidden animate-badgeIn">
                    <div class="flex items-center justify-between gap-2">
                        <div class="bg-primarySoft border-b border-primary/25 px-5 py-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-3-3v6" />
                            </svg>
                            <span class="text-xs font-semibold uppercase tracking-wider text-primary">Attendance
                                History</span>
                        </div>
                        <p class="px-5 py-3 text-sm text-muted">
                            Total: <span id="totalAttendancesCount">0</span>
                        </p>
                    </div>
                    <div class="p-5">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm text-ink">
                                <thead class="bg-surface2 text-xs uppercase tracking-wider text-muted">
                                    <tr>
                                        <th class="px-4 py-3">Subject</th>
                                        <th class="px-4 py-3">Teacher</th>
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceHistoryTable">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
@push('js')
    <script>
        // ==========================================
        // THEME (dark / light)
        // ==========================================
        const rootEl = document.documentElement;

        // ==========================================
        // DOM REFS
        // ==========================================
        const video = document.getElementById('webcam');
        const overlayCanvas = document.getElementById('overlayCanvas');
        const captureBtn = document.getElementById('captureBtn');
        const restartCamBtn = document.getElementById('restartCamBtn');
        const modelStatusBadge = document.getElementById('modelStatusBadge');
        const validationBanner = document.getElementById('validationBanner');
        const statusMessage = document.getElementById('statusMessage');
        const statusIndicatorDot = document.getElementById('statusIndicatorDot');
        const faceCountBadge = document.getElementById('faceCountBadge');
        const cameraPlaceholder = document.getElementById('cameraPlaceholder');
        const radarWrap = document.getElementById('radarWrap');

        const waitingCard = document.getElementById('waitingCard');
        const matchCard = document.getElementById('matchCard');
        const noMatchCard = document.getElementById('noMatchCard');
        const matchPhoto = document.getElementById('matchPhoto');
        const matchName = document.getElementById('matchName');
        const matchRoll = document.getElementById('matchRoll');
        const matchEmail = document.getElementById('matchEmail');
        const matchPhone = document.getElementById('matchPhone');
        const matchConfidence = document.getElementById('matchConfidence');
        const presentBtn = document.getElementById('presentBtn');
        const retakeBtn = document.getElementById('retakeBtn');
        const retryBtn = document.getElementById('retryBtn');

        const scanLog = document.getElementById('scanLog');
        const logEmpty = document.getElementById('logEmpty');
        const logCount = document.getElementById('logCount');

        // ==========================================
        // STATE
        // ==========================================
        let faceLandmarker = null;
        let arcfaceSession = null;
        let isCameraRunning = false;
        let mediaStream = null;
        let animationFrameId = null;
        let matchedStudent = null;
        let currentEmbedding = null;
        let logEntries = 0;

        const ARCFACE_MODEL_URL = '{{ asset('models/arcface.onnx') }}';
        const SIMILARITY_THRESHOLD = {{ $setting->similarity_threshold }};
        const CSRF_TOKEN = '{{ csrf_token() }}';

        if (typeof window.showAlert !== 'function') {
            window.showAlert = function(title, msg) {
                console.log(`[${title}] ${msg}`);
            };
        }

        // ==========================================
        // MODELS
        // ==========================================
        async function initModels() {
            try {
                updateStatus('AI engines initializing…', 'amber');

                const vision = await FilesetResolver.forVisionTasks(
                    "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14/wasm"
                );

                faceLandmarker = await FaceLandmarker.createFromOptions(vision, {
                    baseOptions: {
                        modelAssetPath: `https://storage.googleapis.com/mediapipe-models/face_landmarker/face_landmarker/float16/1/face_landmarker.task`,
                        delegate: "GPU"
                    },
                    runningMode: "VIDEO",
                    numFaces: 2,
                    outputFaceBlendshapes: true
                });

                ort.env.wasm.numThreads = Math.min(4, navigator.hardwareConcurrency || 2);
                arcfaceSession = await ort.InferenceSession.create(ARCFACE_MODEL_URL, {
                    executionProviders: ['webgl', 'wasm', 'cpu']
                });

                modelStatusBadge.className =
                    "text-[11px] px-2.5 py-1 rounded-md bg-verifySoft text-verify border border-verify/25 font-mono";
                modelStatusBadge.innerText = "Engines Ready";

                updateStatus('Requesting camera access…', 'amber');
                startCamera();
            } catch (error) {
                console.error("Model load error:", error);
                modelStatusBadge.className =
                    "text-[11px] px-2.5 py-1 rounded-md bg-dangerSoft text-danger border border-danger/25 font-mono";
                modelStatusBadge.innerText = "Load Failed";
                updateStatus(`Failed to load AI models (${error.message || 'check console'}).`, 'rose');
            }
        }

        // ==========================================
        // CAMERA (auto-starts once models are ready)
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
                    radarWrap.classList.remove('hidden');
                    radarWrap.classList.add('flex');
                    isCameraRunning = true;
                    processVideoFrame();
                };
            } catch (err) {
                console.error("Camera access error:", err);
                updateStatus('Camera access denied or device unavailable.', 'rose');
                cameraPlaceholder.querySelector('p').textContent = 'Camera access denied. Click Restart to try again.';
            }
        }

        restartCamBtn.addEventListener('click', () => {
            stopCamera();
            cameraPlaceholder.classList.remove('hidden');
            cameraPlaceholder.querySelector('p').textContent = 'Requesting camera access…';
            startCamera();
        });

        // ==========================================
        // DETECTION LOOP & VALIDATION
        // ==========================================
        let lastVideoTime = -1;

        async function processVideoFrame() {
            if (!isCameraRunning) return;
            if (video.currentTime !== lastVideoTime) {
                lastVideoTime = video.currentTime;
                const results = await faceLandmarker.detectForVideo(video, performance.now());

                overlayCanvas.width = video.videoWidth;
                overlayCanvas.height = video.videoHeight;
                const ctx = overlayCanvas.getContext('2d');
                ctx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);

                validateAndRenderHUD(results, ctx);
            }
            animationFrameId = requestAnimationFrame(processVideoFrame);
        }

        function validateAndRenderHUD(results, ctx) {
            const numFaces = results.faceLandmarks ? results.faceLandmarks.length : 0;
            faceCountBadge.innerText = `Faces: ${numFaces}`;

            if (numFaces === 0) {
                disableCaptureBtn();
                updateStatus('No face detected. Step in front of the camera.', 'amber');
                return;
            }
            if (numFaces > 1) {
                disableCaptureBtn();
                updateStatus('Multiple faces detected. Only one person at a time.', 'rose');
                return;
            }

            const landmarks = results.faceLandmarks[0];
            const blendshapes = results.faceBlendshapes[0].categories;
            const imgWidth = video.videoWidth,
                imgHeight = video.videoHeight;

            let minX = imgWidth,
                minY = imgHeight,
                maxX = 0,
                maxY = 0;
            landmarks.forEach(pt => {
                const x = pt.x * imgWidth,
                    y = pt.y * imgHeight;
                if (x < minX) minX = x;
                if (x > maxX) maxX = x;
                if (y < minY) minY = y;
                if (y > maxY) maxY = y;
            });

            const boxWidth = maxX - minX,
                boxHeight = maxY - minY;
            const centerX = minX + boxWidth / 2,
                centerY = minY + boxHeight / 2;

            if (centerX < imgWidth * 0.25) {
                flag(ctx, minX, minY, boxWidth, boxHeight, 'Move slightly to the right');
                return;
            }
            if (centerX > imgWidth * 0.75) {
                flag(ctx, minX, minY, boxWidth, boxHeight, 'Move slightly to the left');
                return;
            }
            if (centerY < imgHeight * 0.2) {
                flag(ctx, minX, minY, boxWidth, boxHeight, 'Move slightly down');
                return;
            }
            if (centerY > imgHeight * 0.8) {
                flag(ctx, minX, minY, boxWidth, boxHeight, 'Move slightly up');
                return;
            }

            const faceAreaRatio = (boxWidth * boxHeight) / (imgWidth * imgHeight);
            if (faceAreaRatio < 0.12) {
                flag(ctx, minX, minY, boxWidth, boxHeight, 'Move closer to the camera');
                return;
            }
            if (faceAreaRatio > 0.65) {
                flag(ctx, minX, minY, boxWidth, boxHeight, 'Move farther from the camera');
                return;
            }

            const eyeBlinkLeft = blendshapes.find(b => b.categoryName === 'eyeBlinkLeft')?.score || 0;
            const eyeBlinkRight = blendshapes.find(b => b.categoryName === 'eyeBlinkRight')?.score || 0;
            if (eyeBlinkLeft > 0.5 || eyeBlinkRight > 0.5) {
                flag(ctx, minX, minY, boxWidth, boxHeight, 'Please keep both eyes open');
                return;
            }

            const leftEye = landmarks[33],
                rightEye = landmarks[263],
                nose = landmarks[1];
            const distLeft = Math.abs(nose.x - leftEye.x),
                distRight = Math.abs(rightEye.x - nose.x);
            const yawRatio = distLeft / (distRight + 0.0001);
            if (yawRatio < 0.4 || yawRatio > 2.2) {
                flag(ctx, minX, minY, boxWidth, boxHeight, 'Please look straight at the camera');
                return;
            }

            drawBoundingBox(ctx, minX, minY, boxWidth, boxHeight, 'var(--verify)');
            enableCaptureBtn();
            updateStatus('Face aligned perfectly. Ready to capture!', 'emerald');
        }

        function flag(ctx, x, y, w, h, msg) {
            drawBoundingBox(ctx, x, y, w, h, 'var(--alert)');
            disableCaptureBtn();
            updateStatus(msg, 'amber');
        }

        function drawBoundingBox(ctx, x, y, w, h, colorVar) {
            const color = getComputedStyle(document.documentElement).getPropertyValue(colorVar.replace('var(', '').replace(
                ')', '')) || '#3BC0D3';
            ctx.strokeStyle = color.trim();
            ctx.lineWidth = 2;
            ctx.strokeRect(x, y, w, h);
            const l = 12;
            ctx.strokeStyle = '#ffffff';
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.moveTo(x, y + l);
            ctx.lineTo(x, y);
            ctx.lineTo(x + l, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x + w - l, y);
            ctx.lineTo(x + w, y);
            ctx.lineTo(x + w, y + l);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y + h - l);
            ctx.lineTo(x, y + h);
            ctx.lineTo(x + l, y + h);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x + w - l, y + h);
            ctx.lineTo(x + w, y + h);
            ctx.lineTo(x + w, y + h - l);
            ctx.stroke();
        }

        // ==========================================
        // CAPTURE -> ALIGN -> EMBED -> MATCH
        // ==========================================
        captureBtn.addEventListener('click', async () => {
            if (!isCameraRunning) return;
            updateStatus('Processing & matching against database…', 'amber');
            disableCaptureBtn();

            const results = await faceLandmarker.detectForVideo(video, performance.now());
            if (!results.faceLandmarks || results.faceLandmarks.length === 0) {
                updateStatus('Face lost right at capture. Try again.', 'rose');
                return;
            }

            const landmarks = results.faceLandmarks[0];
            const alignedCanvas = alignAndCropFace(video, landmarks);

            try {
                currentEmbedding = await generateArcFaceEmbedding(alignedCanvas);
                const match = await findMatchingStudent(currentEmbedding);

                stopCamera();
                radarWrap.classList.add('hidden');
                radarWrap.classList.remove('flex');

                if (match) {
                    showMatch(match);
                } else {
                    showNoMatch();
                }
            } catch (err) {
                console.error("Embedding / match failed:", err);
                updateStatus('Biometric verification failed. Try again.', 'rose');
                enableCaptureBtn();
            }
        });

        function alignAndCropFace(videoElement, landmarks) {
            const imgW = videoElement.videoWidth,
                imgH = videoElement.videoHeight;
            const leftEye = {
                x: landmarks[33].x * imgW,
                y: landmarks[33].y * imgH
            };
            const rightEye = {
                x: landmarks[263].x * imgW,
                y: landmarks[263].y * imgH
            };
            const dy = rightEye.y - leftEye.y,
                dx = rightEye.x - leftEye.x;
            const angle = Math.atan2(dy, dx);
            const eyeCenter = {
                x: (leftEye.x + rightEye.x) / 2,
                y: (leftEye.y + rightEye.y) / 2
            };

            const canvas = document.createElement('canvas');
            canvas.width = 112;
            canvas.height = 112;
            const ctx = canvas.getContext('2d');
            ctx.save();
            ctx.translate(56, 45);
            ctx.rotate(-angle);
            const eyeDistance = Math.hypot(dx, dy);
            const scale = 38 / eyeDistance;
            ctx.scale(scale, scale);
            ctx.translate(-eyeCenter.x, -eyeCenter.y);
            ctx.drawImage(videoElement, 0, 0);
            ctx.restore();
            return canvas;
        }

        async function generateArcFaceEmbedding(alignedCanvas) {
            const ctx = alignedCanvas.getContext('2d');
            const {
                data
            } = ctx.getImageData(0, 0, 112, 112);
            const float32Data = new Float32Array(1 * 3 * 112 * 112);
            for (let i = 0; i < 112 * 112; i++) {
                const r = data[i * 4],
                    g = data[i * 4 + 1],
                    b = data[i * 4 + 2];
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
            return l2Normalize(Array.from(outputTensor.data));
        }

        function l2Normalize(vector) {
            const norm = Math.sqrt(vector.reduce((s, v) => s + v * v, 0));
            return vector.map(v => v / (norm + 1e-10));
        }

        function cosineSimilarity(a, b) {
            let dot = 0;
            for (let i = 0; i < a.length; i++) dot += a[i] * b[i];
            return dot;
        }

        // Compare captured embedding against every registered student
        async function findMatchingStudent(embedding) {

            document.getElementById('scannerDiv').classList.add('hidden');
            try {
                const response = await fetch('/students/embeddings');
                if (!response.ok) return null;
                const registeredStudents = await response.json(); // [{ id, name, roll, email, image, embedding }]

                let best = null,
                    bestScore = 0;
                for (const student of registeredStudents) {
                    if (!student.embedding) continue;
                    const stored = typeof student.embedding === 'string' ? JSON.parse(student.embedding) : student
                        .embedding;
                    const score = cosineSimilarity(embedding, stored);
                    if (score > bestScore) {
                        bestScore = score;
                        best = student;
                    }
                }

                if (best && bestScore >= SIMILARITY_THRESHOLD) {
                    return {
                        ...best,
                        similarity: bestScore
                    };
                }


                return null;
            } catch (err) {
                console.error("Error matching face:", err);
                return null;
            }
        }

        // ==========================================
        // RESULT UI
        // ==========================================
        function showMatch(student) {
            matchedStudent = student;
            waitingCard.classList.add('hidden');
            noMatchCard.classList.add('hidden');
            matchCard.classList.remove('hidden');

            matchPhoto.src = student.photo_url || 'https://ui-avatars.com/api/?background=random&name=' +
                encodeURIComponent(student.name || 'U');
            matchName.textContent = student.name || 'Unknown';
            matchRoll.textContent = student.roll ? `Roll: ${student.roll}` : '—';
            matchEmail.textContent = student.email || '—';
            matchPhone.textContent = student.phone || '';
            confidencePercenteage = matchConfidence.textContent = Math.round(student.similarity * 100) + '%';
            document.getElementById('confidenceBar').style.width = confidencePercenteage
            updateConfidenceUI(Math.round(student.similarity * 100));

            presentBtn.disabled = false;
            updateStatus('Identity verified. Ready to mark attendance.', 'emerald');
        }

        function showNoMatch() {
            matchedStudent = null;
            waitingCard.classList.add('hidden');
            matchCard.classList.add('hidden');
            noMatchCard.classList.remove('hidden');
            updateStatus('No match found in database.', 'rose');
        }

        function resetToWaiting() {
            document.getElementById('scannerDiv').classList.remove('hidden')
            matchedStudent = null;
            currentEmbedding = null;
            matchCard.classList.add('hidden');
            noMatchCard.classList.add('hidden');
            waitingCard.classList.remove('hidden');
            cameraPlaceholder.classList.remove('hidden');
            cameraPlaceholder.querySelector('p').textContent = 'Requesting camera access…';
            startCamera();
        }

        retakeBtn.addEventListener('click', resetToWaiting);
        retryBtn.addEventListener('click', resetToWaiting);

        // ==========================================
        // PRESENT -> SAVE ATTENDANCE TO BACKEND
        // ==========================================
        presentBtn.addEventListener('click', async () => {
            if (!matchedStudent) return;

            presentBtn.disabled = true;
            presentBtn.innerHTML = '<span>Saving…</span>';

            try {
                const res = await fetch('/attendance/history/check', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        student_id: matchedStudent.id,
                        similarity: matchedStudent.similarity,
                    })
                });

                const data = await res.json().catch(() => ({}));

                if (res.ok) {
                    document.getElementById('attendanceHistoryCard').classList.remove('hidden');
                    let totalCount = data.total || 0;
                    document.getElementById('totalAttendancesCount').textContent = totalCount;
                    const historyTable = document.getElementById('attendanceHistoryTable');
                    historyTable.innerHTML = '';
                    if (data.history && Array.isArray(data.history)) {
                        data.history.forEach(record => {
                            console.log(record);
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td class="px-4 py-3">${record.session.subject.name || 'N/A'}</td>
                                <td class="px-4 py-3">${record.session.subject.teacher || 'N/A'}</td>
                                <td class="px-4 py-3">${new Date(record.session.date).toLocaleDateString("en-GB", {day: "numeric",month: "long",year: "numeric"}) || 'N/A'}</td>
                                <td class="px-4 py-3"><span
                                            class="inline-flex capitalize px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                            ${record.status || 'N/A'}
                                        </span></td>
                            `;
                            historyTable.appendChild(tr);
                        });
                    }
                } else {
                    const errorMsg = data.message || 'Failed to save attendance.';
                    showAlert('Error', errorMsg, 'error');
                }


            } catch (err) {
                console.error(err);
                showAlert('Error', 'Network error while saving attendance.', 'error');
            } finally {
                presentBtn.disabled = false;
                presentBtn.innerHTML = 'Show History';
            }
        });

        function addLogEntry(student) {
            logEntries += 1;
            logEmpty.remove();
            const time = new Date().toLocaleTimeString('en-GB');
            const li = document.createElement('li');
            li.className = 'flex items-center gap-3 py-2.5 border-b border-border last:border-0';
            li.innerHTML = `
                <span class="w-6 h-6 rounded-full bg-verifySoft text-verify text-[10px] font-mono flex items-center justify-center flex-shrink-0">${logEntries}</span>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium truncate">${student.name || 'Unknown'}</p>
                    <p class="text-[10px] text-muted font-mono">${student.roll ? '#' + student.roll : ''}</p>
                </div>
                <span class="text-[10px] font-mono text-muted flex-shrink-0">${time}</span>
            `;
            scanLog.prepend(li);
            logCount.textContent = `${logEntries} ${logEntries === 1 ? 'entry' : 'entries'}`;
        }

        function updateConfidenceUI(confidenceValue) {
            const confidenceText = document.getElementById('matchConfidence');
            const confidenceBar = document.getElementById('confidenceBar');

            // Percent precision formatting
            const val = Math.min(Math.max(confidenceValue, 0), 100); // 0 threshold checking
            confidenceText.innerText = `${val.toFixed(1)}%`;
            confidenceBar.style.width = `${val}%`;

            // Reset Color Classes
            confidenceText.classList.remove('text-red-500', 'text-yellow-500', 'text-emerald-500', 'text-gray-400');
            confidenceBar.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-emerald-500', 'bg-gray-400');

            // Condition based Color Allocation
            if (val < 50) {
                // Low confidence -> RED
                confidenceText.classList.add('text-red-500');
                confidenceBar.classList.add('bg-red-500');
            } else if (val >= 50 && val < 75) {
                // Medium confidence -> YELLOW
                confidenceText.classList.add('text-yellow-500');
                confidenceBar.classList.add('bg-yellow-500');
            } else {
                // High confidence -> GREEN
                confidenceText.classList.add('text-emerald-500');
                confidenceBar.classList.add('bg-emerald-500');
            }
        }

        // ==========================================
        // UTILITIES
        // ==========================================
        function stopCamera() {
            if (animationFrameId) cancelAnimationFrame(animationFrameId);
            if (mediaStream) mediaStream.getTracks().forEach(t => t.stop());
            isCameraRunning = false;
            video.classList.add('hidden');
            cameraPlaceholder.classList.remove('hidden');
        }

        function enableCaptureBtn() {
            captureBtn.disabled = false;
            captureBtn.className =
                "flex-1 py-3 px-4 rounded-xl font-semibold text-xs uppercase tracking-wider text-white bg-verify hover:opacity-90 transition flex items-center justify-center gap-2 cursor-pointer";
        }

        function disableCaptureBtn() {
            captureBtn.disabled = true;
            captureBtn.className =
                "flex-1 py-3 px-4 rounded-xl font-semibold text-xs uppercase tracking-wider text-white bg-border cursor-not-allowed transition flex items-center justify-center gap-2";
        }

        function updateStatus(msg, theme) {
            statusMessage.innerText = msg;
            if (theme === 'emerald') {
                statusIndicatorDot.className = "w-2 h-2 rounded-full bg-verify";
                validationBanner.className =
                    "mt-4 p-3 rounded-xl bg-verifySoft border border-verify/25 flex items-center justify-between text-xs font-medium text-verify";
            } else if (theme === 'rose') {
                statusIndicatorDot.className = "w-2 h-2 rounded-full bg-danger";
                validationBanner.className =
                    "mt-4 p-3 rounded-xl bg-dangerSoft border border-danger/25 flex items-center justify-between text-xs font-medium text-danger";
            } else {
                statusIndicatorDot.className = "w-2 h-2 rounded-full bg-alert animate-pop";
                validationBanner.className =
                    "mt-4 p-3 rounded-xl bg-surface2 border border-border flex items-center justify-between text-xs font-medium text-muted";
            }
        }

        window.addEventListener('DOMContentLoaded', initModels);
        window.addEventListener('beforeunload', stopCamera);
    </script>
@endpush

@push('css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Space Grotesk"', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        bg: 'var(--bg)',
                        surface: 'var(--surface)',
                        surface2: 'var(--surface-2)',
                        border: 'var(--border)',
                        ink: 'var(--text)',
                        muted: 'var(--text-muted)',
                        accent: 'var(--accent)',
                        accentSoft: 'var(--accent-soft)',
                        verify: 'var(--verify)',
                        verifySoft: 'var(--verify-soft)',
                        alert: 'var(--alert)',
                        alertSoft: 'var(--alert-soft)',
                        danger: 'var(--danger)',
                        dangerSoft: 'var(--danger-soft)',
                    },
                    keyframes: {
                        sweep: {
                            '0%': {
                                transform: 'rotate(0deg)'
                            },
                            '100%': {
                                transform: 'rotate(360deg)'
                            }
                        },
                        badgeIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(10px) scale(0.97)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0) scale(1)'
                            }
                        },
                        pop: {
                            '0%,100%': {
                                opacity: '.5'
                            },
                            '50%': {
                                opacity: '1'
                            }
                        },
                    },
                    animation: {
                        sweep: 'sweep 3.2s linear infinite',
                        badgeIn: 'badgeIn .35s ease-out',
                        pop: 'pop 1.6s ease-in-out infinite',
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/onnxruntime-web/dist/ort.min.js"></script>
    <script type="module">
        import {
            FilesetResolver,
            FaceLandmarker
        } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14/vision_bundle.mjs";
        window.FilesetResolver = FilesetResolver;
        window.FaceLandmarker = FaceLandmarker;
    </script>

    <style>
        :root {
            --bg: #EDF0F0;
            --surface: #FFFFFF;
            --surface-2: #F5F7F7;
            --border: rgba(16, 24, 32, 0.09);
            --text: #101820;
            --text-muted: #5B6B72;
            --accent: #146C7A;
            --accent-soft: rgba(20, 108, 122, 0.10);
            --verify: #218A45;
            --verify-soft: rgba(33, 138, 69, 0.12);
            --alert: #C05F27;
            --alert-soft: rgba(192, 95, 39, 0.12);
            --danger: #C0392E;
            --danger-soft: rgba(192, 57, 46, 0.12);
        }

        html.dark {
            --bg: #0A0F14;
            --surface: #121A21;
            --surface-2: #17212A;
            --border: rgba(255, 255, 255, 0.08);
            --text: #E7ECEF;
            --text-muted: #8FA1AB;
            --accent: #3BC0D3;
            --accent-soft: rgba(59, 192, 211, 0.14);
            --verify: #3FBE5B;
            --verify-soft: rgba(63, 190, 91, 0.14);
            --alert: #F0925A;
            --alert-soft: rgba(240, 146, 90, 0.14);
            --danger: #EB6E6A;
            --danger-soft: rgba(235, 110, 106, 0.14);
        }

        body {
            transition: background-color .25s ease, color .25s ease;
        }

        .glass {
            background: var(--surface);
            border: 1px solid var(--border);
        }

        .radar-ring {
            background: conic-gradient(from 0deg, transparent 0%, var(--accent) 8%, transparent 20%);
            -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 3px), #000 calc(100% - 3px));
            mask: radial-gradient(farthest-side, transparent calc(100% - 3px), #000 calc(100% - 3px));
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 3px;
        }
    </style>
@endpush
