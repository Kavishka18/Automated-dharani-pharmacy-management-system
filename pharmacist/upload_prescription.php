<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmspid']??'') == 0) {
    header('location:logout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dharani PMS - AI Prescription Scanner</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: relative; overflow: hidden; border-radius: 0 0 30px 30px; }
        .header::before { content: ''; position: absolute; inset: 0; background: url('https://images.unsplash.com/photo-1580281773044-0b5577c7a2b2') center/cover; opacity: 0.15; }
        .card { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border-radius: 24px; box-shadow: 0 25px 60px rgba(0,0,0,0.25); border: none; }
        .upload-zone {
            border: 4px dashed #667eea; border-radius: 24px; padding: 60px 20px; text-align: center; cursor: pointer;
            transition: all 0.4s ease; background: rgba(102,126,234,0.05);
        }
        .upload-zone:hover { border-color: #764ba2; background: rgba(118,75,162,0.1); transform: translateY(-10px); }
        .upload-zone.dragover { border-color: #38ef7d; background: rgba(56,239,125,0.15); }
        .btn-ai { background: linear-gradient(45deg, #667eea, #764ba2); border: none; border-radius: 50px; padding: 16px 40px; font-weight: 600; font-size: 1.2rem; box-shadow: 0 15px 35px rgba(102,126,234,0.4); }
        .btn-ai:hover { transform: translateY(-8px); box-shadow: 0 25px 50px rgba(102,126,234,0.6); }
        #receipt { max-width: 500px; margin: 40px auto; padding: 35px; border: 3px dashed #667eea; border-radius: 24px; background: white; box-shadow: 0 30px 80px rgba(102,126,234,0.3); font-family: 'Courier New', monospace; animation: fadeIn 1s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        .receipt-header { background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; font-size: 2.3rem; }
        .drug-item { background: rgba(102,126,234,0.1); padding: 14px; border-radius: 14px; margin: 10px 0; border-left: 6px solid #667eea; }
        .print-btn { position: fixed; bottom: 30px; right: 30px; background: linear-gradient(45deg, #e74c3c, #c0392b); color: white; width: 80px; height: 80px; border-radius: 50%; font-size: 2.5rem; box-shadow: 0 20px 50px rgba(231,76,60,0.6); z-index: 9999; border: none; }
        .print-btn:hover { transform: scale(1.2); }
        .time-info { background: rgba(255,255,255,0.2); border-radius: 12px; padding: 12px; border-left: 5px solid white; }
        @media print { body * { visibility: hidden; } #receipt, #receipt * { visibility: visible; } #receipt { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); border: 2px dashed #000; width: 90%; } .print-btn, .no-print { display: none !important; } }
    </style>
</head>
<body>
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <div class="header pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body text-center text-white">
                    <h1 class="display-3 font-weight-bold">AI Prescription Scanner</h1>
                    <p class="lead">Upload prescription → Get medicines in 8 seconds!</p>
                    <div class="time-info mt-3">
                        <small>Sri Lanka Time: <?php echo date('d M Y, h:i A'); ?> (UTC+5:30)</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt--8">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card">
                        <div class="card-header bg-gradient-primary text-white text-center">
                            <h2 class="mb-0">Groq AI Vision Engine (Llama-4-Scout)</h2>
                        </div>
                        <div class="card-body p-5">
                            <div class="upload-zone" id="dropZone">
                                <i class="fas fa-cloud-upload-alt fa-5x text-primary mb-4"></i>
                                <h3>Click or Drag & Drop Prescription</h3>
                                <p class="text-muted">JPG / PNG only • Max 4MB</p>
                                <input type="file" id="fileInput" accept=".jpg,.jpeg,.png" style="display:none;">
                            </div>
                            <div class="text-center mt-4">
                                <button class="btn btn-ai text-white" id="analyzeBtn" style="display:none;">Analyze with Groq AI</button>
                            </div>
                            <div id="result" class="mt-5"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Receipt -->
            <div id="receipt" style="display:none;"></div>

            <!-- Manual Review Panel -->
            <div id="reviewPanel" style="display:none; margin-top:30px;">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h4>Manual Review Mode</h4>
                        <p>AI might be wrong. Please correct before printing</p>
                    </div>
                    <div class="card-body">
                        <div class="form-group"><label>Patient Name</label><input type="text" id="edit_patient" class="form-control"></div>
                        <div class="form-group"><label>Doctor Name</label><input type="text" id="edit_doctor" class="form-control"></div>
                        <div class="form-group"><label>Medicines (one per line)</label><textarea id="edit_medicines" class="form-control" rows="6"></textarea></div>
                        <div class="form-group"><label>Diagnosis</label><textarea id="edit_diagnosis" class="form-control" rows="3"></textarea></div>
                        <div class="text-center">
                            <button class="btn btn-success btn-lg" onclick="saveAndPrint()">Save & Print</button>
                            <button class="btn btn-secondary" onclick="cancelReview()">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Print -->
            <button onclick="window.print()" class="btn print-btn no-print" id="printBtn" style="display:none;">Print</button>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>

    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script>
        let aiData = null;
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const analyzeBtn = document.getElementById('analyzeBtn');
        const resultDiv = document.getElementById('result');
        const receipt = document.getElementById('receipt');
        const printBtn = document.getElementById('printBtn');

        dropZone.onclick = () => fileInput.click();
        dropZone.ondragover = e => { e.preventDefault(); dropZone.classList.add('dragover'); };
        dropZone.ondragleave = () => dropZone.classList.remove('dragover');
        dropZone.ondrop = e => { e.preventDefault(); dropZone.classList.remove('dragover'); if (e.dataTransfer.files[0]) { fileInput.files = e.dataTransfer.files; updateFileName(); } };
        fileInput.onchange = updateFileName;

        function updateFileName() {
            if (fileInput.files[0]) {
                dropZone.innerHTML = `<i class="fas fa-file-image fa-5x text-success mb-4"></i><h3>${fileInput.files[0].name}</h3><p class="text-success">Ready!</p>`;
                analyzeBtn.style.display = 'inline-block';
            }
        }

        analyzeBtn.onclick = () => {
            const formData = new FormData();
            formData.append('prescription_file', fileInput.files[0]);
            resultDiv.innerHTML = `<div class="alert alert-info text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><br>AI reading... please wait</div>`;

            fetch('process_prescription.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    aiData = data.data;
                    const d = data.data;
                    const meds = Array.isArray(d.drug_names) ? d.drug_names : [];
                    const diag = Array.isArray(d.clinical_description) ? d.clinical_description : [];
                    const isUnsure = !d.patient_name || !d.doctor_name || meds.length < 2;

                    receipt.innerHTML = `
                        <div class="text-center mb-4">
                            <h2 class="receipt-header">DHARANI AI PRESCRIPTION</h2>
                            <p><strong>Patient:</strong> ${d.patient_name || '<span class="text-danger">Not detected</span>'}</p>
                            <p><strong>Doctor:</strong> ${d.doctor_name || '<span class="text-danger">Not detected</span>'} • <strong>Date:</strong> ${d.date || 'Today'}</p>
                        </div>
                        <div style="border-top: 2px dashed #667eea; margin: 20px 0;"></div>
                        <h4>Medicines:</h4>
                        ${meds.length ? meds.map(m => `<div class="drug-item">Pill ${m}</div>`).join('') : '<p class="text-danger">No medicines found!</p>'}
                        ${diag.length ? `<h4 class="mt-4">Diagnosis:</h4>` + diag.map(c => `<div class="drug-item">Diagnosis ${c}</div>`).join('') : ''}
                        <div style="border-top: 2px dashed #667eea; margin: 20px 0;"></div>
                        <div class="text-center mt-4">
                            <p><em>Powered by Groq + Llama-4-Scout • ${data.tokens} tokens</em></p>
                            ${isUnsure ? '<p class="text-warning"><strong>AI is not confident. Please review!</strong></p>' : ''}
                        </div>
                    `;
                    receipt.style.display = 'block';
                    printBtn.style.display = 'none';

                    resultDiv.innerHTML = `
                        <div class="text-center mt-4">
                            <button class="btn btn-success btn-lg mr-3" onclick="acceptAndPrint()">Accept & Print</button>
                            <button class="btn btn-warning btn-lg" onclick="flagForReview()">Flag for Review</button>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `<div class="alert alert-danger">Error: ${data.error}</div>`;
                }
            })
            .catch(() => resultDiv.innerHTML = `<div class="alert alert-danger">No internet</div>`);
        };

        function acceptAndPrint() { printBtn.style.display = 'block'; resultDiv.innerHTML = '<div class="alert alert-success">Ready! Click red button to print</div>'; }
        function flagForReview() {
            document.getElementById('reviewPanel').style.display = 'block';
            document.getElementById('edit_patient').value = aiData.patient_name || '';
            document.getElementById('edit_doctor').value = aiData.doctor_name || '';
            document.getElementById('edit_medicines').value = Array.isArray(aiData.drug_names) ? aiData.drug_names.join('\n') : '';
            document.getElementById('edit_diagnosis').value = Array.isArray(aiData.clinical_description) ? aiData.clinical_description.join('\n') : '';
            receipt.style.display = 'none';
        }
        function saveAndPrint() {
            const patient = document.getElementById('edit_patient').value || 'Unknown';
            const doctor = document.getElementById('edit_doctor').value || 'Unknown';
            const meds = document.getElementById('edit_medicines').value.trim().split('\n').filter(m => m.trim());
            const diag = document.getElementById('edit_diagnosis').value.trim().split('\n').filter(d => d.trim());

            receipt.innerHTML = `
                <div class="text-center mb-4"><h2 class="receipt-header">DHARANI PHARMACY</h2>
                <p><strong>Patient:</strong> ${patient}</p>
                <p><strong>Doctor:</strong> ${doctor} • <strong>Date:</strong> ${new Date().toLocaleDateString('en-GB')}</p></div>
                <div style="border-top: 2px dashed #667eea; margin: 20px 0;"></div>
                <h4>Medicines (MANUALLY CORRECTED):</h4>
                ${meds.map(m => `<div class="drug-item">Pill ${m}</div>`).join('')}
                ${diag.length ? `<h4 class="mt-4">Diagnosis:</h4>` + diag.map(c => `<div class="drug-item">Diagnosis ${c}</div>`).join('') : ''}
                <div style="border-top: 2px dashed #667eea; margin: 20px 0;"></div>
                <div class="text-center"><p><em>Verified by Pharmacist • Gampaha</em></p></div>
            `;
            document.getElementById('reviewPanel').style.display = 'none';
            receipt.style.display = 'block';
            printBtn.style.display = 'block';
            resultDiv.innerHTML = '<div class="alert alert-success">Corrected! Ready to print</div>';
        }
        function cancelReview() { document.getElementById('reviewPanel').style.display = 'none'; receipt.style.display = 'block'; }
    </script>
</body>
</html>