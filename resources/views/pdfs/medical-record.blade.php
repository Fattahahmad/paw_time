<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Medical Record #{{ $medicalRecord->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #68C4CF;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #68C4CF;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #68C4CF;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info-row {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .info-value {
            display: inline-block;
        }
        .content-box {
            border: 1px solid #ddd;
            padding: 12px;
            background-color: #f9f9f9;
            margin-bottom: 15px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🐾 Paw Time Veterinary Clinic</h1>
        <p>Medical Record Document</p>
        <p>Record ID: #{{ $medicalRecord->id }} | Generated: {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Patient Information</div>
        <div class="info-row">
            <span class="info-label">Pet Name:</span>
            <span class="info-value">{{ $medicalRecord->appointment->pet->pet_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Pet Type:</span>
            <span class="info-value">{{ $medicalRecord->appointment->pet->species }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Breed:</span>
            <span class="info-value">{{ $medicalRecord->appointment->pet->breed ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Owner:</span>
            <span class="info-value">{{ $medicalRecord->appointment->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Owner Contact:</span>
            <span class="info-value">{{ $medicalRecord->appointment->user->email }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Appointment Details</div>
        <div class="info-row">
            <span class="info-label">Appointment Date:</span>
            <span class="info-value">{{ $medicalRecord->appointment->appointment_date->format('d M Y, H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="info-value">{{ ucfirst($medicalRecord->appointment->status) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Veterinarian:</span>
            <span class="info-value">{{ $medicalRecord->createdBy->name }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Diagnosis</div>
        <div class="content-box">
            {!! nl2br(e($medicalRecord->diagnosis)) !!}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Treatment</div>
        <div class="content-box">
            {!! nl2br(e($medicalRecord->treatment)) !!}
        </div>
    </div>

    @if($medicalRecord->prescription)
    <div class="section">
        <div class="section-title">Prescription</div>
        <div class="content-box">
            {!! nl2br(e($medicalRecord->prescription)) !!}
        </div>
    </div>
    @endif

    @if($medicalRecord->appointment->veterinarian_notes)
    <div class="section">
        <div class="section-title">Veterinarian Notes</div>
        <div class="content-box">
            {!! nl2br(e($medicalRecord->appointment->veterinarian_notes)) !!}
        </div>
    </div>
    @endif

    @if($medicalRecord->has_attachments)
    <div class="section">
        <div class="section-title">Attachments</div>
        <div class="info-row">
            {{ $medicalRecord->attachment_count }} file(s) attached
        </div>
    </div>
    @endif

    <div class="footer">
        <p>This is an official medical record from Paw Time Veterinary Clinic</p>
        <p>Generated on {{ now()->format('d M Y, H:i:s') }}</p>
    </div>
</body>
</html>
