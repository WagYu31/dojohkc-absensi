# ─── Stage 1: Builder (compile dlib) ─────────────────────────────────────────
FROM python:3.11-slim AS builder

# Install build deps untuk dlib
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    cmake \
    libopenblas-dev \
    liblapack-dev \
    git \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /build
COPY requirements.txt .

# Build wheel dlib (sekali, di-cache Docker layer)
RUN pip wheel --no-cache-dir --wheel-dir /wheels -r requirements.txt


# ─── Stage 2: Runtime ─────────────────────────────────────────────────────────
FROM python:3.11-slim

# Runtime libs
RUN apt-get update && apt-get install -y --no-install-recommends \
    libopenblas0 \
    liblapack3 \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Install dari wheels yang sudah di-compile
COPY --from=builder /wheels /wheels
RUN pip install --no-cache-dir --find-links=/wheels /wheels/*.whl

COPY main.py .

EXPOSE 8000

# Healthcheck
HEALTHCHECK --interval=10s --timeout=5s --retries=5 \
    CMD python -c "import urllib.request; urllib.request.urlopen('http://localhost:8000/health')"

CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8000", "--workers", "2"]
