
# coding: utf-8

# In[1]:


import numpy
print(numpy.__version__)


# In[2]:


from platform import python_version
print("Current Python Version-", python_version())


# In[3]:


import tensorflow
print(tensorflow.__version__)


# In[4]:


import h5py

print("HDF5 Version:", h5py.__version__)


# In[5]:


import keras
print(keras.__version__)


# In[6]:


import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import cv2
import os

NUM_EPOCHS = 20
STEPS_PER_EPOCH_TRAINING = 4
STEPS_PER_EPOCH_VALIDATION = 2
BATCH_SIZE_TRAINING = 16
BATCH_SIZE_VALIDATION = 8
BATCH_SIZE_TESTING = 1
NUM_CLASSES = 2  # Change this to the number of classes in your dataset

import keras
from keras.layers import Conv2D, MaxPooling2D, Flatten, Dense, Dropout
from keras.models import Sequential
from keras import optimizers
from keras import regularizers
from keras.layers.normalization import BatchNormalization

def cnn(input_shape=(200, 150, 3), num_classes=NUM_CLASSES):
    model = Sequential()
    model.add(Conv2D(64, kernel_size=(4, 4), activation='relu', input_shape=input_shape))
    model.add(BatchNormalization())
    model.add(MaxPooling2D(pool_size=(2, 4)))

    model.add(Conv2D(64, (3, 5), activation='relu', kernel_regularizer=regularizers.l2(0.04)))
    model.add(MaxPooling2D(pool_size=(2, 2)))
    model.add(Dropout(0.2))

    model.add(Conv2D(64, (2, 2), activation='relu'))
    model.add(BatchNormalization())
    model.add(MaxPooling2D(pool_size=(2, 2)))
    model.add(Dropout(0.2))

    model.add(Flatten())

    model.add(Dense(64, activation='relu', kernel_regularizer=regularizers.l2(0.04)))
    model.add(Dropout(0.5))
    model.add(Dense(32, activation='relu', kernel_regularizer=regularizers.l2(0.04)))

    model.add(Dense(num_classes, activation='softmax'))

    model.compile(
        loss=keras.losses.categorical_crossentropy,
        optimizer=optimizers.Adam(lr=0.001, beta_1=0.9, beta_2=0.999, epsilon=1e-08, decay=0.0),
        metrics=['accuracy']
    )

    model.summary()
    return model

model = cnn()

from keras.applications.vgg16 import preprocess_input
from keras.preprocessing.image import ImageDataGenerator

data_generator = ImageDataGenerator(preprocessing_function=preprocess_input)



train_generator = data_generator.flow_from_directory(
    'dataset/train',
    target_size=(200, 150),
    batch_size=BATCH_SIZE_TRAINING,
    class_mode='categorical'
)

validation_generator = data_generator.flow_from_directory(
    'dataset/test',
    target_size=(200, 150),
    batch_size=BATCH_SIZE_VALIDATION,
    class_mode='categorical'
)

fit_history = model.fit_generator(
    train_generator,
    steps_per_epoch=STEPS_PER_EPOCH_TRAINING,
    epochs=NUM_EPOCHS,
    validation_data=validation_generator,
    validation_steps=STEPS_PER_EPOCH_VALIDATION
)


model.save('model_cnn_new.h5')

#print(fit_history.history.keys())

plt.figure(1, figsize = (15,8)) 
    
plt.subplot(221)  
plt.plot(fit_history.history['acc'])  
plt.plot(fit_history.history['val_acc'])  
plt.title('model accuracy')  
plt.ylabel('accuracy')  
plt.xlabel('epoch')  
plt.legend(['train', 'valid']) 
    
plt.subplot(222)  
plt.plot(fit_history.history['loss'])  
plt.plot(fit_history.history['val_loss'])  
plt.title('model loss')  
plt.ylabel('loss')  
plt.xlabel('epoch')  
plt.legend(['train', 'valid']) 
plt.savefig("accuracy.png")
plt.show()
plt.close() 


# In[2]:


import numpy as np
from keras.models import load_model

model = load_model('model_cnn_new.h5')

from keras.applications.vgg16 import preprocess_input
from keras.preprocessing.image import ImageDataGenerator

data_generator = ImageDataGenerator(preprocessing_function=preprocess_input)

test_generator = data_generator.flow_from_directory(
    directory='test',
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

