<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    /* Floating Action Button Styles */
    .fab-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1050;
    }

    .fab-main {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #FF6B6B;
        border: none;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }

    .fab-main::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #FF6B6B;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 50%;
    }

    .fab-main:hover::before {
        opacity: 1;
    }

    .fab-main:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .fab-main.active {
        transform: rotate(45deg);
        background: linear-gradient(45deg, #ff4757, #ff3838);
    }

    .fab-main i {
        transition: transform 0.3s ease;
        position: relative;
        z-index: 2;
    }

    /* Action Buttons */
    .fab-actions {
        position: absolute;
        bottom: 70px;
        right: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .fab-action {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        opacity: 0;
        transform: translateY(20px) scale(0);
        position: relative;
        text-decoration: none;
    }

    .fab-action::before {
        content: attr(data-tooltip);
        position: absolute;
        right: 60px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .fab-action::after {
        content: '';
        position: absolute;
        right: 55px;
        top: 50%;
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-left-color: rgba(0, 0, 0, 0.8);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .fab-action:hover::before,
    .fab-action:hover::after {
        opacity: 1;
    }

    .fab-action:hover {
        transform: translateX(-10px) scale(1.1);
        color: white;
        text-decoration: none;
    }

    /* Action Button Colors */
    .fab-action.back {
        background: linear-gradient(45deg, #6c757d, #5a6268);
    }

    .fab-action.edit {
        background: linear-gradient(45deg, #007bff, #0056b3);
    }

    .fab-action.resolve {
        background: linear-gradient(45deg, #28a745, #1e7e34);
    }

    .fab-action.comment {
        background: linear-gradient(45deg, #ffc107, #e0a800);
    }

    .fab-action.create {
        background: linear-gradient(45deg, #007bff, #0056b3);
    }

    .fab-action.filter {
        background: linear-gradient(45deg, #17a2b8, #138496);
    }

    .fab-action.sync {
        background: linear-gradient(45deg, #28a745, #1e7e34);
    }

    /* Animation States */
    .fab-container.active .fab-action {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .fab-container.active .fab-action:nth-child(1) {
        transition-delay: 0.1s;
    }

    .fab-container.active .fab-action:nth-child(2) {
        transition-delay: 0.15s;
    }

    .fab-container.active .fab-action:nth-child(3) {
        transition-delay: 0.2s;
    }

    .fab-container.active .fab-action:nth-child(4) {
        transition-delay: 0.25s;
    }

    /* Backdrop */
    .fab-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.2);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1040;
    }

    .fab-backdrop.active {
        opacity: 1;
        visibility: visible;
    }

    /* Pulse Animation */
    .fab-main.pulse::after {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        border: 2px solid rgba(255, 107, 107, 0.6);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(0.8);
            opacity: 1;
        }

        100% {
            transform: scale(1.3);
            opacity: 0;
        }
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .fab-container {
            bottom: 20px;
            right: 20px;
        }

        .fab-main {
            width: 56px;
            height: 56px;
            font-size: 20px;
        }

        .fab-action {
            width: 44px;
            height: 44px;
            font-size: 16px;
        }

        .fab-action::before {
            right: 50px;
            font-size: 12px;
            padding: 6px 10px;
        }
    }

    #dataTable tbody tr {
        cursor: pointer;
    }

    #dataTable tbody tr:hover {
        background-color: #f8f9fa !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #dataTable tbody tr button {
        cursor: pointer;
        position: relative;
        z-index: 1;
    }

    /* Enhanced Drop Zone with Glow Effects */
    .drop-zone {
        border: 2px dashed #ccc;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    /* Subtle hover effect */
    .drop-zone:hover {
        border-color: #007bff;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
    }

    /* Dramatic drag-over effect with glow */
    .drop-zone.drag-over {
        border-color: #28a745;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        transform: scale(1.05) translateY(-3px);
        box-shadow:
            0 0 30px rgba(40, 167, 69, 0.4),
            0 0 60px rgba(40, 167, 69, 0.2),
            0 15px 35px rgba(0, 0, 0, 0.1);
        animation: pulseGlow 1.5s ease-in-out infinite;
    }

    /* Pulsing glow animation */
    @keyframes pulseGlow {

        0%,
        100% {
            box-shadow:
                0 0 30px rgba(40, 167, 69, 0.4),
                0 0 60px rgba(40, 167, 69, 0.2),
                0 15px 35px rgba(0, 0, 0, 0.1);
        }

        50% {
            box-shadow:
                0 0 40px rgba(40, 167, 69, 0.6),
                0 0 80px rgba(40, 167, 69, 0.3),
                0 20px 40px rgba(0, 0, 0, 0.15);
        }
    }

    /* Shimmer effect on drag over */
    .drop-zone.drag-over::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg,
                transparent 30%,
                rgba(40, 167, 69, 0.3) 50%,
                transparent 70%);
        border-radius: 12px;
        animation: shimmer 2s linear infinite;
        z-index: -1;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    /* Enhanced text styling */
    .drop-zone-text {
        color: #6c757d;
        font-size: 16px;
        font-weight: 500;
        pointer-events: none;
        text-align: center;
        transition: all 0.3s ease;
        z-index: 1;
        position: relative;
    }

    .drop-zone:hover .drop-zone-text {
        color: #007bff;
        transform: translateY(-2px);
    }

    .drop-zone.drag-over .drop-zone-text {
        color: #28a745;
        font-weight: 600;
        transform: scale(1.1) translateY(-3px);
        text-shadow: 0 2px 10px rgba(40, 167, 69, 0.3);
    }

    .drop-zone-text i {
        font-size: 32px;
        display: block;
        margin-bottom: 12px;
        color: #007bff;
        transition: all 0.3s ease;
    }

    .drop-zone:hover .drop-zone-text i {
        color: #0056b3;
        transform: scale(1.1) rotateY(10deg);
    }

    .drop-zone.drag-over .drop-zone-text i {
        color: #28a745;
        transform: scale(1.2) rotateY(0deg);
        animation: bounce 0.6s ease-in-out;
        filter: drop-shadow(0 4px 8px rgba(40, 167, 69, 0.4));
    }

    @keyframes bounce {

        0%,
        20%,
        53%,
        80%,
        100% {
            transform: scale(1.2) translateY(0);
        }

        40%,
        43% {
            transform: scale(1.2) translateY(-8px);
        }

        70% {
            transform: scale(1.2) translateY(-4px);
        }

        90% {
            transform: scale(1.2) translateY(-2px);
        }
    }

    /* File list styling */
    .selected-file-item {
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .selected-file-item:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
        border-color: #dee2e6;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .file-info {
        flex: 1;
        overflow: hidden;
    }

    .file-info strong {
        color: #495057;
        transition: color 0.2s ease;
    }

    .selected-file-item:hover .file-info strong {
        color: #007bff;
    }

    .remove-file {
        opacity: 0.6;
        transition: all 0.2s ease;
        border-radius: 50%;
    }

    .remove-file:hover {
        opacity: 1;
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        transform: scale(1.1);
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    #selectedFilesContainer {
        max-height: 250px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 0.75rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        transition: all 0.3s ease;
    }

    #selectedFilesContainer:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    /* Success state for when files are selected */
    .drop-zone.has-files {
        border-color: #28a745;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        box-shadow: 0 4px 20px rgba(40, 167, 69, 0.1);
    }

    .drop-zone.has-files .drop-zone-text {
        color: #28a745;
    }

    .drop-zone.has-files .drop-zone-text i {
        color: #28a745;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .drop-zone {
            padding: 30px 15px;
            min-height: 100px;
        }

        .drop-zone-text i {
            font-size: 28px;
        }

        .drop-zone-text {
            font-size: 14px;
        }

        .selected-file-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .selected-file-item .remove-file {
            align-self: flex-end;
            margin-top: 8px;
        }
    }

    /* Custom scrollbar for file list */
    #selectedFilesContainer::-webkit-scrollbar {
        width: 6px;
    }

    #selectedFilesContainer::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    #selectedFilesContainer::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    #selectedFilesContainer::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>