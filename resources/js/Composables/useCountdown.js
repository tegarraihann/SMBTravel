import { ref, onMounted, onUnmounted } from 'vue'

export function useCountdown(targetDate) {
  const days = ref(0)
  const hours = ref(0)
  const minutes = ref(0)
  const seconds = ref(0)
  const isExpired = ref(false)

  let interval = null

  const calculateTimeRemaining = () => {
    const now = new Date().getTime()
    const target = new Date(targetDate).getTime()
    const difference = target - now

    if (difference > 0) {
      days.value = Math.floor(difference / (1000 * 60 * 60 * 24))
      hours.value = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
      minutes.value = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60))
      seconds.value = Math.floor((difference % (1000 * 60)) / 1000)
      isExpired.value = false
    } else {
      days.value = 0
      hours.value = 0
      minutes.value = 0
      seconds.value = 0
      isExpired.value = true
    }
  }

  const startCountdown = () => {
    calculateTimeRemaining()
    interval = setInterval(calculateTimeRemaining, 1000)
  }

  const stopCountdown = () => {
    if (interval) {
      clearInterval(interval)
      interval = null
    }
  }

  onMounted(() => {
    startCountdown()
  })

  onUnmounted(() => {
    stopCountdown()
  })

  return {
    days,
    hours,
    minutes,
    seconds,
    isExpired,
    startCountdown,
    stopCountdown
  }
}