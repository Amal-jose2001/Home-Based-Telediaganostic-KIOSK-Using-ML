import numpy as np
from keras.models import load_model

model = load_model('model_cnn_new.h5')

from keras.applications.vgg16 import preprocess_input
from keras.preprocessing.image import ImageDataGenerator

data_generator = ImageDataGenerator(preprocessing_function=preprocess_input)

test_generator = data_generator.flow_from_directory(
    directory='test/',
    target_size=(200, 150),
    batch_size=1,
    class_mode=None,  # This should be None since you're not using labels
    shuffle=False,
    seed=123
)

pred = model.predict_generator(test_generator, steps=len(test_generator), verbose=1)
print(pred)
predicted_class_indices = np.argmax(pred, axis=1)
print(predicted_class_indices)
label = ['NORMAL', 'PNEUMONIA']
print(label[predicted_class_indices[0]])
