(function (window) {
  function shuffleArray(array) {
    const copy = Array.isArray(array) ? array.slice() : [];

    for (let i = copy.length - 1; i > 0; i -= 1) {
      const j = Math.floor(Math.random() * (i + 1));
      [copy[i], copy[j]] = [copy[j], copy[i]];
    }

    return copy;
  }

  function encodeImagePath(path) {
    if (!path) return '';

    if (
      path.startsWith('http://') ||
      path.startsWith('https://') ||
      path.startsWith('blob:') ||
      path.startsWith('data:')
    ) {
      return path;
    }

    const hashIndex = path.indexOf('#');
    const queryIndex = path.indexOf('?');
    let pathname = path;
    let query = '';
    let hash = '';

    if (queryIndex !== -1 && (hashIndex === -1 || queryIndex < hashIndex)) {
      pathname = path.substring(0, queryIndex);
      query = path.substring(queryIndex, hashIndex !== -1 ? hashIndex : undefined);
    }

    if (hashIndex !== -1) {
      if (queryIndex === -1 || hashIndex < queryIndex) {
        pathname = path.substring(0, hashIndex);
      }
      hash = path.substring(hashIndex);
    }

    const encodedPath = pathname
      .split('/')
      .map((segment) => {
        if (segment === '') return '';

        try {
          return encodeURIComponent(decodeURIComponent(segment));
        } catch (error) {
          return encodeURIComponent(segment);
        }
      })
      .join('/');

    return encodedPath + query + hash;
  }

  window.SyzygyUtils = {
    shuffleArray,
    encodeImagePath,
  };
})(window);
