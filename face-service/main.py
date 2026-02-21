"""
Face Recognition Service — FastAPI
Endpoint:
  POST /api/face/encode   → ambil foto, return face encoding (128-d)
  POST /api/face/verify   → bandingkan dua encoding, return hasil
  GET  /health            → health check
"""

from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
import face_recognition
import numpy as np
import base64
import json
import re
from io import BytesIO
from PIL import Image

app = FastAPI(title="Face Recognition Service", version="1.0.0")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)


# ── Models ─────────────────────────────────────────────────────────────────────

class EncodeRequest(BaseModel):
    photo_base64: str          # data:image/jpeg;base64,<data>

class VerifyRequest(BaseModel):
    photo_base64: str          # foto live (data:image/...)
    stored_encoding: str       # JSON array 128 float dari DB


# ── Helpers ────────────────────────────────────────────────────────────────────

def base64_to_rgb(photo_base64: str) -> np.ndarray:
    """Convert base64 image string ke numpy array RGB."""
    # Hapus prefix data:image/...;base64,
    if "," in photo_base64:
        photo_base64 = photo_base64.split(",", 1)[1]

    try:
        img_bytes = base64.b64decode(photo_base64)
        img = Image.open(BytesIO(img_bytes)).convert("RGB")
        return np.array(img)
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Gambar tidak valid: {e}")


def get_encoding(img_array: np.ndarray) -> list:
    """Deteksi wajah dan kembalikan encoding 128-d."""
    # Gunakan model HOG (cepat) atau CNN (akurat)
    # model="hog" lebih cepat, "cnn" lebih akurat
    locations = face_recognition.face_locations(img_array, model="hog")

    if not locations:
        raise HTTPException(status_code=422, detail="Wajah tidak terdeteksi dalam foto")

    if len(locations) > 1:
        raise HTTPException(status_code=422, detail="Terdeteksi lebih dari 1 wajah. Pastikan hanya ada 1 wajah")

    encodings = face_recognition.face_encodings(img_array, known_face_locations=locations)

    if not encodings:
        raise HTTPException(status_code=422, detail="Gagal mengekstrak data wajah")

    return encodings[0].tolist()   # float list 128 elemen


# ── Routes ─────────────────────────────────────────────────────────────────────

@app.get("/health")
def health():
    return {"status": "ok", "service": "face-recognition"}


@app.post("/api/face/encode")
def encode_face(req: EncodeRequest):
    """
    Terima foto base64, kembalikan face encoding (128 float).
    Disimpan di DB sebagai JSON array via face-save.php.
    """
    img = base64_to_rgb(req.photo_base64)
    encoding = get_encoding(img)

    return {
        "status":   "success",
        "encoding": encoding,          # list 128 float
        "message":  "Wajah berhasil di-encode"
    }


@app.post("/api/face/verify")
def verify_face(req: VerifyRequest):
    """
    Bandingkan foto live dengan encoding tersimpan.
    Kembalikan: match (bool), distance (float), confidence (%)
    """
    # Decode stored encoding
    try:
        stored = json.loads(req.stored_encoding)
        stored_np = np.array(stored, dtype=np.float64)
        if stored_np.shape != (128,):
            raise ValueError("Encoding tidak valid (bukan 128-d)")
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Stored encoding tidak valid: {e}")

    # Encode foto live
    img = base64_to_rgb(req.photo_base64)
    live_encoding = get_encoding(img)
    live_np = np.array(live_encoding, dtype=np.float64)

    # Bandingkan (threshold default face_recognition = 0.6)
    THRESHOLD = 0.55       # lebih ketat (0.4 = sangat ketat, 0.6 = longgar)
    distances = face_recognition.face_distance([stored_np], live_np)
    distance  = float(distances[0])
    match     = bool(distance <= THRESHOLD)

    # Confidence: 0% = sangat berbeda, 100% = identik
    confidence = max(0.0, round((1 - distance / THRESHOLD) * 100, 1)) if match else round(max(0, (THRESHOLD - distance) / THRESHOLD * 100), 1)

    return {
        "status":     "success",
        "match":      match,
        "distance":   round(distance, 4),
        "confidence": confidence,
        "threshold":  THRESHOLD,
        "message":    "Verifikasi berhasil ✓" if match else f"Wajah tidak cocok (jarak: {distance:.3f})"
    }
